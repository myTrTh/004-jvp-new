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
$user->username = 'Jack';
$user->email = 'wolf373@mail.ru';
$user->password = $auth->encodePassword('1111');
$user->is_active = 1;
$user->save();