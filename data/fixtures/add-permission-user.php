<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\User;

$container = new ServiceProvider();
$container->get()['db'];

// ROLE USER
$user = User::where('id', 1)->first();
$user->permissions()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9]);
$user->save();