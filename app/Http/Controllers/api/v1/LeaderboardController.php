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
    /**
     * @Endpoint(name="Get ", description="Returns a leaderboard")
     * @QueryParam(name="key", type="string", status="required", description="Leaderboard key")
     * @Queryparam(name="secret", type="string", status="required", description="Leaderboard secret")
     * @ResponseExample(status=200, file="responses/leaderboard/leaderboard.game.get.login-200.json")
     */
    public function getLeaderboard($key, $secret)
    {
        $leaderboard = Leaderboard::where("key", $key)
            ->where("secret", $secret)
            ->get()
            ->first();

        return response()->json(["leaderboard" => $leaderboard]);
    }


    /**
     * @Endpoint(name="Create", description="Creates a new leaderboard and generates a key and secret")
     * @QueryParam(name="name", type="string", status="required", description="The name of the leaderboard")
     * @ResponseExample(status=200, file="responses/user/leaderboard.create-200.json")
     */
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

    /**
     * @Endpoint(name="Update", description="Updates a leaderboard")
     * @QueryParam(name="id", type="string", status="required", description="The id of the leaderboard")
     * @BodyParam(name="name", type="string", status="required", description="The name of the leaderboard")
     * @BodyParam(name="revoke", type="string", status="required", description="Wether of not the key and secret should be regnerated")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "name" => "sometimes|string",
            "revoke" => "sometimes|boolean",
        ]);

        $leaderboard = Leaderboard::find($id);

        if (!$leaderboard) {
            return response()->json(["message" => "couldn't find leaderboard with id '$id'"], 400);
        }

        $leaderboard->name = $data["name"] ?? $leaderboard->name;

        if (isset($data["revoke"]) && $data["revoke"]) {
            $leaderboard->secret = Hash::make(Str::uuid());
            $leaderboard->save();
        }
    }


    /**
     * @Endpoint(name="All", description="Returns all leaderboards")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function all()
    {
        $leaderboards = Leaderboard::where("user_id", Auth::id())
            ->get();

        return response()->json(["leaderboards" => $leaderboards]);
    }

    /**
     * @Endpoint(name="Get", description="Returns all leaderboards")
     * @QueryParam(name="id", type="string", status="required", description="The id of the leaderboard")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function get($id)
    {
        $leaderboard = Leaderboard::find($id);

        if (!$leaderboard) {
            return response()->json(["message" => "invalid id or wrong permission"], 400);
        }

        if ($leaderboard->user_id != Auth::id()) {
            return response()->json(["message" => "invalid id or wrong permission"], 400);
        }


        return response()->json(["leaderboard" => $leaderboard]);
    }

    /**
     * @Endpoint(name="Delete", description="Deletes a leaderboard")
     * @QueryParam(name="id", type="string", status="required", description="The id of the leaderboard")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function delete($id)
    {
        $leaderboard = Leaderboard::find($id);

        if (!$leaderboard) {
            return response()->json(["message" => "invalid id or wrong permission"], 400);
        }

        if ($leaderboard->user_id != Auth::id()) {
            return response()->json(["message" => "invalid id or wrong permission"], 400);
        }
    }
}
