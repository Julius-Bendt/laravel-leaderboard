<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    const DOCUMENTPATH = "auth";
    const LOGIN_ROUTE = "user.login";
    const REGISTER_ROUTE = "user.register";


    public function testLoginWithWrongCredentials()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route(self::LOGIN_ROUTE), [
            "email" => "test@test.com",
            "password" => "not-a-password",
            ]);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::LOGIN_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(401);
    }

    public function testRegister()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route(self::REGISTER_ROUTE), [
            "email" => "test@test.com",
            "password" => "test",
            "name" => "testname"
            ]);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::REGISTER_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }

    public function testLogin()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route(self::LOGIN_ROUTE), [
            "email" => "test@test.com",
            "password" => "test",
            ]);

        $this->WriteDocumentation(self::DOCUMENTPATH, self::LOGIN_ROUTE,$response->getStatusCode(), $response->getContent());

        $response
        ->assertStatus(200);
    }
}
