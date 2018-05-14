<?php
namespace Core;

use App\Core\View;
use App\Core\ServiceProvider;

class ViewTest extends \Codeception\Test\Unit
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
    public function testViewRender()
    {
        $container = new ServiceProvider('test');
        $view = new View($container->get());

        $this->assertContains('Вход', $view->render('auth/login.html.twig'));
        $this->assertContains('Вход', $view->render('auth/login.html.twig', array('test' => 'test')));
        $this->assertNotContains('Другое', $view->render('auth/login.html.twig'));
    }
}