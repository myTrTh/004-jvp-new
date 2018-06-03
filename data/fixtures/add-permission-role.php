<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\Role;

$container = new ServiceProvider();
$container->get()['db'];

// ROLE USER
$role_user = Role::where('role', 'ROLE_USER')->first();
$role_user->permissions()->sync([1, 2, 3, 4]);
$role_user->save();

// ROLE MODERATOR
$role_admin = Role::where('role', 'ROLE_MODERATOR')->first();
$role_admin->permissions()->sync([5, 6, 7, 8, 9]);
$role_admin->save();

// ROLE ADMIN
$role_super_admin = Role::where('role', 'ROLE_ADMIN')->first();
$role_super_admin->permissions()->sync([10, 11, 12]);
$role_super_admin->save();

// ROLE SUPER ADMIN
$role_super_admin = Role::where('role', 'ROLE_SUPER_ADMIN')->first();
$role_super_admin->permissions()->sync([13, 14, 15]);
$role_super_admin->save();