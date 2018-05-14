<?php
namespace Utils;

use App\Utils\TwigExtension;
use App\Core\ServiceProvider;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Session\Session;

class TwigExtensionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $appextension;
    private $container;

    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->twigextension = new TwigExtension($this->container->get());
    }

    protected function _after()
    {
    }

    // tests
    public function testTwigExtension()
    {
        $this->assertInstanceOf(TwigFunction::class, $this->twigextension->getFunctions()[0]);

        $this->assertArrayHasKey('app', $this->twigextension->getGlobals());

        $token = $this->twigextension->generateCSRFtoken();

        $session = new Session();

        $this->assertEquals($token, $session->get('csrf_token'));

        $session->set('user_id', 1);
        $this->assertTrue($this->twigextension->isAccess('ROLE_ADMIN'));
        
        $this->assertFalse($this->twigextension->isAccess('ROLE_FALSE'));
    }
}