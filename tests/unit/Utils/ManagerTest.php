<?php
namespace Utils;

use App\Core\ServiceProvider;
use App\Utils\Manager;

class ManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $manager;
    
    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->container = $this->container->get();
        $this->manager = new Manager($this->container);
    }

    protected function _after()
    {
    }

    // tests
    public function testIfEmptyString()
    {
        $string = '';
        $this->assertEquals('Поле не может быть пустым.', $this->manager->ifEmptyStringValidate($string));
    }
}