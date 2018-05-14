<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\Role;

$container = new ServiceProvider();
$container->get()['db'];

$role_user = new Role();
$role_user->role = 'ROLE_USER';
$role_user->save();

$role_moderator = new Role();
$role_moderator->role = 'ROLE_MODERATOR';
$role_moderator->save();

$role_admin = new Role();
$role_admin->role = 'ROLE_ADMIN';
$role_admin->save();

$role_super_admin = new Role();
$role_super_admin->role = 'ROLE_SUPER_ADMIN';
$role_super_admin->save();