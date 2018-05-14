<?php
namespace Core;

use App\Core\Router;
use App\Core\ServiceProvider;

class RouterTest extends \Codeception\Test\Unit
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
    public function testRun()
    {
        $container = new ServiceProvider('test');
        $container = $container->get();
        $router = $container['router'];

        $this->assertArrayHasKey('_controller', $router->run());
        $this->assertArrayNotHasKey('_somekey', $router->run());
    }
}