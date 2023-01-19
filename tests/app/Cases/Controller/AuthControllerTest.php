<?php

namespace App\Cases\Controller;

use App\AppTest;
use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\Model;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthControllerTest extends AppTest
{
    /*    protected function setUp(): void
        {
            parent::setUp();

        }*/
    public function testRegister()
    {
        $data = [
            "email" => "test@gmail.com",
            "password" => "12345678",
            "first_name" => "TestName",
            "last_name" => "TestLastName"
        ];
        $body = json_encode($data);
        $userModel = model(UserModel::class, true, $this->db);

        $this->assertNull(($userModel->where('email', $data['email'])->first()));

        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('register');
        $this->assertInstanceOf(User::class, $userModel->where('email', $data['email'])->first());
        $this->assertTrue($result->isOK());
    }

/*    public function testLogin()
    {
        $body = json_encode([
            "email" => "test@gmail.com",
            "password" => "12345678"
        ]);

        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('login');

        $this->assertTrue($result->isOK());
    }*/
}