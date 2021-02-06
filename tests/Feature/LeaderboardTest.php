<?php

namespace Tests\Feature;

use App\Models\Leaderboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    const DOCUMENTPATH = "leaderboard";

    const CREATE_ROUTE = "leaderboard.create";
    const UPDATE_ROUTE = "leaderboard.update";
    const GAMEGET_ROUTE = "leaderboard.game.get";
    const ALL_ROUTE = "leaderboard.all";
    const GET_ROUTE = "leaderboard.get";
    const DELETE_ROUTE = "leaderboard.delete";



    public function testCreateLeaderboard()
    {
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::CREATE_ROUTE), [
            "name" => "test_leaderboard_start",
            ]);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::CREATE_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

    public function testUpdateLeaderboard()
    {
        $this->withoutExceptionHandling();

        $id = Leaderboard::latest("created_at")->get()->first()->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::UPDATE_ROUTE,$id), [
            "revoke" => true,
        ]);

        $response
        ->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::UPDATE_ROUTE,$id), [
            "name" => "test_leaderboard_updated",
        ]);

        $response
        ->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->post(route(self::UPDATE_ROUTE,$id), [
            "revoke" => true,
            "name" => "test_leaderboard"
        ]);

        $response
        ->assertStatus(200);




        $this->WriteDocumentation(self::DOCUMENTPATH, self::UPDATE_ROUTE,$response->getStatusCode(), $response->getContent());

    }

    public function testGetGameLeaderboards()
    {
        $this->withoutExceptionHandling();

        $key = "";
        $secret = "";

        $route = route(self::GAMEGET_ROUTE, [$key, $secret]);

         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::GAMEGET_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

    public function testGetLeaderboard()
    {
        $this->withoutExceptionHandling();

        $id = Leaderboard::latest("created_at")->get()->first()->id;

        $route = route(self::GET_ROUTE, [$id]);

         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::GET_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

    public function testGetAllUserLeaderboards()
    {
        $this->withoutExceptionHandling();

        $route = route(self::ALL_ROUTE, []);

         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->get($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::ALL_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

    public function testDeleteLeaderboard()
    {
        $this->withoutExceptionHandling();

        $id = Leaderboard::latest("created_at")->get()->first()->id;

        $route = route(self::DELETE_ROUTE, [$id]);

         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken(),
        ])->delete($route);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::DELETE_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

}
