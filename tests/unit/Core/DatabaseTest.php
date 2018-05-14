<?php
namespace Core;

use App\Core\ServiceProvider;
use App\Model\User;

class DatabaseTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testDb()
    {
        $this->container = new ServiceProvider('test');
        $this->container = $this->container->get();

        $this->container['db'];

        $user = new User();
        $user->username = 'user_for_db_test';
        $user->email = 'user_for_db_test@gmail.com';
        $user->password = '1111';
        $user->is_active = 1;
        $user->save();

        $user->forceDelete();
    }
}