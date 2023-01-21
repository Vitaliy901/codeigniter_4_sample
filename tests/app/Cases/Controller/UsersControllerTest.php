<?php

namespace App\Cases\Controller;

use App\AppTest;
use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DataException;
use Tests\Support\Fixtures\UsersFixture;

class UsersControllerTest extends AppTest
{
    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = model(UserModel::class, true, $this->db);
        $this->createAuthUser();
    }

    public function testCreateSuccess()
    {
        $data = UsersFixture::registerUser();
        $body = json_encode($data);

        $this->assertNull($this->userModel->where('email', $data['email'])->first());
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\Users::class)
            ->execute('create');
        $this->assertInstanceOf(User::class, $this->userModel->where('email', $data['email'])->first());
        $result->assertJSONFragment([
            'user' => [
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'role' => $data['role']
            ]
        ]);
        $this->assertTrue($result->isOK());
    }

    public function testUpdateSuccess()
    {
        $registerData = UsersFixture::registerUser();
        $registerBody = json_encode($registerData);

        $registerResult = $this->withBody($registerBody)
            ->controller(\App\Controllers\V1\Users::class)
            ->execute('create');
        $this->assertNotNull($this->userModel->where('email', $registerData['email'])->first());
        $id = json_decode($registerResult->getJSON())->user->id;

        $data = UsersFixture::updateUser();
        $body = json_encode($data);

        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\Users::class)
            ->execute('update', $id);

        $result->assertJSONFragment([
            'user' => [
                'id' => $id,
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'role' => $data['role']
            ]
        ]);

        $this->assertTrue($result->isOK());
    }

    public function testUpdateCredEmpty()
    {
        $data = UsersFixture::registerUser();

        $body = json_encode([]);
        $this->expectException(DataException::class);
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\Users::class)
            ->execute('update');
        $this->assertFalse($result->isOK());
    }

    public function testDeleteSuccess()
    {
        $registerData = UsersFixture::registerUser();
        $registerBody = json_encode($registerData);

        $registerResult = $this->withBody($registerBody)
            ->controller(\App\Controllers\V1\Users::class)
            ->execute('create');
        $this->assertNotNull($this->userModel->where('email', $registerData['email'])->first());
        $id = json_decode($registerResult->getJSON())->user->id;

        $result = $this->controller(\App\Controllers\V1\Users::class)
            ->execute('delete', $id);
        $this->assertNull($this->userModel->where('email', $registerData['email'])->first());
        $result->assertJSONFragment([
            'user' => [],
            'status' => 'Success',
        ]);
        $this->assertTrue($result->isOK());
    }

        private function createAuthUser()
    {
        $data = UsersFixture::userAdmin();
        $body = json_encode($data);

        $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('register');
        $result = $this->withBody($body)
            ->controller(\App\Controllers\V1\AuthController::class)
            ->execute('login');
        $token = json_decode($result->getJSON())->access_token;
        $request = service('request');
        $request->setHeader('Authorization', 'Bearer ' . $token);
    }
}