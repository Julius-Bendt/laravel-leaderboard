<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Score;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ScoreController extends Controller
{

    /**
     * @Endpoint(name="Create", description="Deletes a leaderboard")
     * @BodyParam(name="username", type="string", status="required", description="Username for the user")
     * @BodyParam(name="score", type="integer", status="required", description="Score to put into the user")
     * @BodyParam(name="key", type="string", status="required", description="Leaderboard key")
     * @BodyParam(name="secret", type="string", status="required", description="Leaderboard Secret")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function createOrUpdate(Request $request)
    {
        $data = $request->validate([
            "username" => "required|string",
            "score" => "required|integer",
            "key" => "required|string",
            "secret" => "required|string"
        ]);

        $leaderboardId = $this->validateTable($data["key"], $data["secret"]);

        $scoreModel = Score::where("leaderboard_id", $leaderboardId)
            ->where("username", $data["username"])
            ->get()
            ->first();

        if (!$scoreModel) {
            $messages["method"] = "created";
            $scoreModel =  Score::create([
                "username" => $data["username"],
                "score" => $data["score"],
                "leaderboard_id" => $leaderboardId,
            ]);
        } else {
            if ($data["score"] > $scoreModel->score) {
                $messages["method"] = "updated";
                $scoreModel->score = $data["score"];
                $scoreModel->save();
            } else {
                $messages["method"] = "notthing";
            }
        }

        return response()->json($messages);
    }

    /**
     * @Endpoint(name="Fetch", description="Deletes a leaderboard")
     * @QueryParam(name="username", type="string", status="required", description="Username for the user")
     * @QueryParam(name="score", type="integer", status="required", description="Score to put into the user")
     * @QueryParam(name="key", type="string", status="required", description="Leaderboard key")
     * @QueryParam(name="secret", type="string", status="required", description="Leaderboard Secret")
     * @ResponseExample(status=200, file="responses/user/leaderboard.fetch-200.json")
     */
    public function fetch($key, $secret, $limit, $offset)
    {
        $leaderboardId = $this->validateTable($key, $secret);

        $scores = Score::where("leaderboard_id", $leaderboardId)
            ->offset($offset)
            ->limit($limit)
            ->orderBy("score", "desc")
            ->get();

        return response()->json(["scores" => $scores]);
    }

        /**
     * @Endpoint(name="Create", description="Deletes a leaderboard")
     * @QueryParam(name="id", type="integer", status="required", description="Leaderboard id")
     * @QueryParam(name="limit", type="integer", status="required", description="Limit for pagnation")
     * @QueryParam(name="query", type="integer", status="required", description="Offset for pagnation")
     * @ResponseExample(status=200, file="responses/user/leaderboard.update-200.json")
     */
    public function dashboardFetch($id, $limit, $offset)
    {
        $scores = Score::where("leaderboard_id", $id)
            ->offset($offset)
            ->limit($limit)
            ->orderBy("score", "desc")
            ->get();

        return response()->json(["scores" => $scores]);
    }

    public function amount($key, $secret)
    {
        $leaderboardId = $this->validateTable($key, $secret);

        $amount = Score::where("leaderboard_id", $leaderboardId)
            ->count();

        return response()->json(["amount" => $amount]);
    }


    public function validateTable($key, $secret)
    {
        $leaderboard = Leaderboard::where("key", $key)
            ->where("secret", $secret)
            ->get()
            ->first();

        if (!$leaderboard) {
            throw new InvalidArgumentException("invalid table key or secret");
        }

        return $leaderboard->id;
    }
}
