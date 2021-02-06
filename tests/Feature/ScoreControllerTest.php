<?php

namespace Tests\Feature;

use App\Models\Leaderboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class ScoreControllerTest extends TestCase
{
    const DOCUMENTPATH = "auth";

    const CREATE_ROUTE = "score.create";
    const LEADERBOARDCREATE_ROUTE = "leaderboard.create";
    const AMOUNT_ROUTE = "score.amount";
    const FETCH_ROUTE = "score.fetch";
    const GETFROMLEADERBOARD_ROUTE = "score.dashboard.fetch";

    public function testCreateOrUpdate()
    {
        $this->withoutExceptionHandling();

        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::LEADERBOARDCREATE_ROUTE), [
            "name" => "test_leaderboard_start",
        ]);


        $leaderboard = Leaderboard::where("name", "test_leaderboard_start")->get()->first();
        $key = $leaderboard->key;
        $secret = $leaderboard->secret;


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::CREATE_ROUTE), [
            "username" => "testuser",
            "score" => 100,
            "key" => $key,
            "secret" => $secret
        ]);


        $this->WriteDocumentation(self::DOCUMENTPATH, self::CREATE_ROUTE, $response->getStatusCode(), $response->getContent());

        $response
            ->assertStatus(200);
    }

    public function testGetAmountOfScores()
    {
        $this->withoutExceptionHandling();

        $leaderboard = Leaderboard::where("name", "test_leaderboard")->get()->first();
        $key = $leaderboard->key;
        $secret = $leaderboard->secret;

        $route = route(self::AMOUNT_ROUTE, [$key, $secret]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::AMOUNT_ROUTE, $response->getStatusCode(), $response->getContent());

        $response
            ->assertStatus(200);
    }

    public function testFetchScores()
    {
        $this->withoutExceptionHandling();

        $leaderboard = Leaderboard::where("name", "test_leaderboard")->get()->first();
        $key = $leaderboard->key;
        $secret = $leaderboard->secret;

        $route = route(self::FETCH_ROUTE, [$key, $secret, 0, 10]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::FETCH_ROUTE, $response->getStatusCode(), $response->getContent());

        $response
            ->assertStatus(200);
    }

    public function testGetScoreFromLeaderboardId()
    {
        $this->withoutExceptionHandling();

        $leaderboard = Leaderboard::where("name", "test_leaderboard")->get()->first();

        $route = route(self::GETFROMLEADERBOARD_ROUTE, [$leaderboard->id, 10, 0]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::GETFROMLEADERBOARD_ROUTE, $response->getStatusCode(), $response->getContent());

        $response
            ->assertStatus(200);
    }
}
