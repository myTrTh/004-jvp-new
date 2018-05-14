<?php
namespace Model;

use App\Core\ServiceProvider;
use App\Model\User;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    
    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->container = $this->container->get();        
    }

    protected function _after()
    {
    }

    // tests
    public function testRole()
    {
        $user = User::where('id', 1)->first();

        $this->assertInternalType('object', $user->roles);
    }
}