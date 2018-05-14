<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\User;

$container = new ServiceProvider();
$container->get()['db'];

$role_for_admin = User::where('id', 1)->first();
$role_for_admin->roles()->sync([1, 2, 3, 4]);
$role_for_admin->save();