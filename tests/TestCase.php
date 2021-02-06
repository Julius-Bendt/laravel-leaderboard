<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function accessToken()
    {
        $user = User::where("email","test@test.com")->get()->first();
        return $user->createToken("test_token")->plainTextToken;
    }

    public function WriteDocumentation(string $path, string $route, int $statusCode, ?string $jsonContent)
    {
        try
        {

            $fileSystem = $this->app->make("filesystem");
            $filename = sprintf("%s-%d.json",$route,$statusCode);
            $filepath = sprintf("responses/%s/%s",$path,$filename);


            //Ensures that jsonContent is printed pretty
            $data = json_decode($jsonContent);
            $jsonContentPretty = json_encode($data, JSON_PRETTY_PRINT);

            $fileSystem->disk("local")->put($filepath,$jsonContentPretty);

        }
        catch (BindingResolutionException $exception)
        {
            print("can't document " . $route . " route. \nerror: " . $exception);
        }
    }
}
