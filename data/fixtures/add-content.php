<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\Content;

$container = new ServiceProvider();
$container->get()['db'];

$content = new Content();
$content->type = 'rules';
$content->title = 'Правила';
$content->article = '';
$content->user_id = 1;
$content->save();

$content = new Content();
$content->type = 'faq';
$content->title = 'FAQ';
$content->article = '';
$content->user_id = 1;
$content->save();

$content = new Content();
$content->type = 'about';
$content->title = 'О нас';
$content->article = '';
$content->user_id = 1;
$content->save();

$content = new Content();
$content->type = 'alert';
$content->title = 'Оповещения';
$content->article = '';
$content->user_id = 1;
$content->save();