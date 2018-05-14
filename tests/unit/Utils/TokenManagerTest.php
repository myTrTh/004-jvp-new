<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

class TokenManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $serviceProvider;
    private $tokenManager;

    protected function _before()
    {
        $this->serviceProvider = new ServiceProvider('test');
        $this->tokenManager = $this->serviceProvider->get()['tokenManager'];
    }

    protected function _after()
    {
    }

    // tests
    public function testGenerageAndCheckToken()
    {
        $token = $this->tokenManager->generateCSRFtoken();
        
        $this->assertNotNull($token);
        
        $this->assertNull($this->tokenManager->checkCSRFtoken($token));

        $this->assertEquals('CSRF Token is not valid', $this->tokenManager->checkCSRFtoken('wrong-token'));
    }
}