<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\User;

$container = new ServiceProvider();
$container->get()['db'];

$auth = $container->get()['authManager'];

$user = new User();
$user->username = 'admin';
$user->email = 'admin@myframework.ru';
$user->password = $auth->encodePassword('1111');
$user->is_active = 1;
$user->save();