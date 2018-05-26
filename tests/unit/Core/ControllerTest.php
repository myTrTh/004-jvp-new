<?php
namespace Core;

use App\Core\Controller;
use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ControllerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $controller;
    
    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->controller = new Controller($this->container->get());
    }

    protected function _after()
    {
    }

    // tests
    public function testControllerRender()
    {
        $this->assertInstanceOf(Response::class, $this->controller->render('app/index.html.twig'));
    }

    public function testRedirectUrl()
    {
        $this->assertInstanceOf(RedirectResponse::class, $this->controller->redirectUrl('/error/404'));
    }

    public function testControllerRedirectToRoute()
    {
        $this->assertInstanceOf(RedirectResponse::class, $this->controller->redirectToRoute('app_index'));
    }

    public function testPageKeeper()
    {
        $default = 1;
        $this->assertEquals($default, $this->controller->getPageNumber());

        $page = 4;
        $this->controller->pageKeeper($page);
        $this->assertEquals($page, $this->controller->getPageNumber());
    }
}