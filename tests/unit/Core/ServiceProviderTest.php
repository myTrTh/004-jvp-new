<?php
namespace Core;

use App\Core\ServiceProvider;

class ServiceProviderTest extends \Codeception\Test\Unit
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
    public function testContainer()
    {
        $container = new ServiceProvider();

        $this->assertArrayHasKey('config', $container->get());
        $this->assertArrayNotHasKey('noconfig', $container->get());
    }
}