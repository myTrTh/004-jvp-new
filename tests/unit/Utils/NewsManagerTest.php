<?php
namespace Utils;

use App\Core\ServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Model\News;
use Codeception\Util\ReflectionHelper;

class NewsManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    private $container;
    private $token;    
    
    protected function _before()
    {
        $this->container = new ServiceProvider('test');
        $this->container = $this->container->get();

        $this->token = 'token';

        $this->session = new Session();
        $this->session->set('csrf_token', $this->token);
    }

    protected function _after()
    {
    }

    // tests
    public function testAdd()
    {
        // wrong validate
        $request = new Request([
            'title' => 'Заголовок',
            'article' => 'Cтатья',
            '_csrf_token' => 'wrong_token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['newsManager']->add($request));

        $request = new Request([
            'title' => 'Заголовок',
            'article' => 'Cтатья',
            '_csrf_token' => $this->token
        ]);

        $request = new Request([
            'title' => '',
            'article' => 'Cтатья',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Заголовок не может быть пустым.', $this->container['newsManager']->add($request));        

        $request = new Request([
            'title' => 'Заголовок',
            'article' => '',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Текст не может быть пустым.', $this->container['newsManager']->add($request));        

        $request = new Request([
            'title' => 'Заголовок',
            'article' => 'Текст',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Вы не авторизированы.', $this->container['newsManager']->add($request));

        $this->session->set('user_id', 1);

        $title = 'Специальный заголовок для теста 01';
        $request = new Request([
            'title' => $title,
            'article' => 'Текст',
            '_csrf_token' => $this->token
        ]);

        $this->tester->dontSeeInDatabase('news', array('title' => $title));

        $this->assertNull($this->container['newsManager']->add($request));

        $this->tester->seeInDatabase('news', array('title' => $title));

        $news = News::where('title', $title)->first();
        $news->forceDelete();
    }

    public function testEdit()
    {
        $id = 2;

        // wrong validate
        $request = new Request([
            'title' => 'Тест для редактирования измененный',
            'article' => 'Статья для редактирования',
            '_csrf_token' => 'wrong_token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['newsManager']->edit($id, $request));

        // wrong validate
        $request = new Request([
            'title' => '',
            'article' => 'Статья для редактирования',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Заголовок не может быть пустым.', $this->container['newsManager']->edit($id, $request));

        // wrong validate
        $request = new Request([
            'title' => 'Тест для редактирования измененный',
            'article' => '',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Текст не может быть пустым.', $this->container['newsManager']->edit($id, $request));

        // wrong validate
        $wrong_id = 999;
        $request = new Request([
            'title' => 'Тест для редактирования измененный',
            'article' => 'Статья для редактирования',
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Такой новости не существует', $this->container['newsManager']->edit($wrong_id, $request));

        // wrong validate
        $request = new Request([
            'title' => 'Тест для редактирования измененный',
            'article' => 'Статья для редактирования',
            '_csrf_token' => $this->token
        ]);

        $this->assertNull($this->container['newsManager']->edit($id, $request));

        $this->tester->seeInDatabase('news', array('title' => 'Тест для редактирования измененный'));

        $news = News::where('title', 'Тест для редактирования измененный')->first();
        $news->title = 'Тест для редактирования';
        $news->save();

    }

    public function testDelete()
    {
        $news = new News();
        $news->title = 'Тест для удаления';
        $news->article = 'Тест для удаления';
        $news->user_id = 1;
        $news->save();

        $wrong_id = 999;
        $request = new Request([
            '_csrf_token' => $this->token
        ]);

        $this->assertEquals('Такой новости не существует', $this->container['newsManager']->delete($wrong_id, $request));

        $request = new Request([
            '_csrf_token' => 'wrong_token'
        ]);

        $this->assertEquals('CSRF Token is not valid', $this->container['newsManager']->delete($wrong_id, $request));        
        $this->tester->seeInDatabase('news', array('title' => 'Тест для удаления', 'deleted_at' => NULL));

        $news = News::where('title', 'Тест для удаления')->first();

        $request = new Request([
            '_csrf_token' => $this->token
        ]);

        $this->assertNull($this->container['newsManager']->delete($news->id, $request));

        $news->forceDelete();

        $this->tester->dontSeeInDatabase('news', array('title' => 'Тест для удаления', 'deleted_at' => NULL));
    }
}