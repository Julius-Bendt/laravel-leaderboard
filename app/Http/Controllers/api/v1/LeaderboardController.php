<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    public function getLeaderboard($key, $secret)
    {
        $leaderboard = Leaderboard::where("key",$key)
        ->where("secret", $secret)
        ->get()
        ->first();

        return response()->json(["leaderboard" => $leaderboard]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string"
        ]);

        $secret = Hash::make(Str::uuid());
        $key = Str::random(32);

        $leaderboard = Leaderboard::create([
            "secret" => $secret,
            "key" => $key,
            "name" => $data["name"],
            "user_id" => Auth::id(),
        ]);

        return response()->json(["key" => $key, "secret" => $secret]);

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "name" => "sometimes|string",
            "revoke" => "sometimes|boolean",
        ]);

        $leaderboard = Leaderboard::find($id);

        if(!$leaderboard)
        {
            return response()->json(["message" => "couldn't find leaderboard with id '$id'"],400);
        }

        $leaderboard->name = $data["name"]??$leaderboard->name;

        if(isset($data["revoke"]) && $data["revoke"])
        {
            $leaderboard->secret = Hash::make(Str::uuid());
            $leaderboard->save();
        }
    }

    public function all()
    {
        $leaderboards = Leaderboard::where("user_id", Auth::id())
        ->get();

        return response()->json(["leaderboards" => $leaderboards]);
    }

    public function get($id)
    {
        $leaderboard = Leaderboard::find($id);

        if(!$leaderboard)
        {
            return response()->json(["message" => "invalid id or wrong permission"],400);
        }

        if($leaderboard->user_id != Auth::id())
        {
            return response()->json(["message" => "invalid id or wrong permission"],400);
        }


        return response()->json(["leaderboard" => $leaderboard]);
    }

    public function delete($id)
    {
        $leaderboard = Leaderboard::find($id);

        if(!$leaderboard)
        {
            return response()->json(["message" => "invalid id or wrong permission"],400);
        }

        if($leaderboard->user_id != Auth::id())
        {
            return response()->json(["message" => "invalid id or wrong permission"],400);
        }
    }
}
