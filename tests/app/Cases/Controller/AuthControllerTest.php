<?php

namespace App\Cases\Controller;

use App\AppTest;
use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\Model;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Fixtures\UsersFixture;

class AuthControllerTest extends AppTest
{
    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = model(UserModel::class, true, $this->db);
    }

    public function testRegister()
    {
        $data = UsersFixture::registerUser();
        $body = json_encode($data);

        $this->assertNull($this->userModel->where('email', $data['email'])->first());
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('register');
        $this->assertInstanceOf(User::class, $this->userModel->where('email', $data['email'])->first());
        $result->assertJSONFragment([
            'user' => [
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name']
            ],
            'status' => 'Success',
        ]);
        $this->assertTrue($result->isOK());
    }

    public function testLoginSuccess()
    {
        $registerData = UsersFixture::registerUser();
        $registerBody = json_encode($registerData);
        $this->withBody($registerBody)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('register');

        $data = UsersFixture::loginUser();
        $body = json_encode($data);

        $this->assertInstanceOf(User::class, $this->userModel->where('email', $data['email'])->first());
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('login');

        $result->assertJSONFragment([
            'status' => 'Success',
        ]);
        $this->assertTrue($result->isOK());
    }

    public function testLoginUserEmpty()
    {
        $data = UsersFixture::loginUser();
        $body = json_encode($data);

        $this->assertNull($this->userModel->where('email', $data['email'])->first());
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('login');

        $result->assertJSONFragment([
            'status' => 'Not found',
        ]);
        $result->assertStatus(404);
        $this->assertFalse($result->isOK());
    }

    public function testLoginWrongPassword()
    {
        $registerData = UsersFixture::registerUser();
        $registerBody = json_encode($registerData);
        $this->withBody($registerBody)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('register');

        $data = UsersFixture::loginUser();
        $data['password'] = 'wrong_password';
        $body = json_encode($data);

        $this->assertInstanceOf(User::class, $this->userModel->where('email', $data['email'])->first());
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('login');

        $result->assertJSONFragment([
            'status' => 'Not found',
        ]);
        $result->assertStatus(404);
        $this->assertFalse($result->isOK());
    }
}