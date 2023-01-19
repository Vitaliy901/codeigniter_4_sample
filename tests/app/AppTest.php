<?php
namespace App;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

abstract class AppTest extends CIUnitTestCase
{
    use ControllerTestTrait, DatabaseTestTrait;
    // For Migrations
    protected $migrate     = true;
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = \Config\Database::connect('tests');
    }
}