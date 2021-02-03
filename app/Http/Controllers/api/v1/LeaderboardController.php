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

        if($data["revoke"] != null && $data["revoke"])
        {
            $leaderboard->secret = Hash::make(Str::uuid());
        }
    }


    public function pagnate($key,$secret, $limit, $offset)
    {
        $leaderboard = Leaderboard::where("key",$key)
        ->where("secret",$secret)
        ->get()
        ->first();

        if(!$leaderboard)
        {
            return response()->json(["message" => "invalid table key or secret"],400);
        }


        $scores = Score::where("table_id",$leaderboard->id)
        ->offset($offset)
        ->limit($limit)
        ->get();

        return response()->json(["scores" => $scores]);

    }

    public function amount($id)
    {
        $scores = Score::where("table_id",$id)
        ->count();
    }
}
