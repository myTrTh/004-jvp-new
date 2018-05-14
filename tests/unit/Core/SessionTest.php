<?php
namespace Core;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionTest extends \Codeception\Test\Unit
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
    public function testActivateSession()
    {
        session_destroy();

        $container = new ServiceProvider('test');
        $container = $container->get();

        $session = $container['session'];
        $session->activate();

        $this->assertContains('/../var/sessions/', session_save_path());
    }
}