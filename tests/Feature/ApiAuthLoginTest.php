<?php

namespace Tests\Feature;

use App\Http\Resources\ApiLoginResponse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthLoginTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_login_success()
    {
        $user = [
            'name' => "testing",
            'email' => "testing@gmail.com",
            'password' => Hash::make(12345678)];

        User::create($user);

        $response = $this->post('/api/v1/auth/login', [
            'email' => "testing@gmail.com",
            'password' => "12345678",
        ]);

        $data = (new ApiLoginResponse(auth()->user()))->toArray(request());

//        $data = json_decode(json_encode(new ApiLoginResponse(auth()->user())), true);

        $response
            ->assertStatus(200)
            ->assertJson(['data' => $data]);
    }

    public function test_login_without_email()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => "",
            'password' => "12345678",
        ]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.login_validation.email_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_login_without_password()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => "abc@gmail.com",
            'password' => "",
        ]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.login_validation.password_required')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_login_with_password_minimum_condition()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => "abc@gmail.com",
            'password' => "123",
        ]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.login_validation.password_min')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }

    public function test_login_with_email_email_condition()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => "abc@",
            'password' => "12345678",
        ]);

        $error = [
            "status" => "validation failed",
            "error" => [config('code.login_validation.email_email')]
        ];

        $response->assertStatus(422)->assertJson($error);
    }
}
