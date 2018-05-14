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
$role_user->permissions()->sync([1]);
$role_user->save();

// ROLE MODERATOR
$role_admin = Role::where('role', 'ROLE_MODERATOR')->first();
$role_admin->permissions()->sync([2, 3, 4, 5]);
$role_admin->save();

// ROLE ADMIN
$role_super_admin = Role::where('role', 'ROLE_ADMIN')->first();
$role_super_admin->permissions()->sync([6]);
$role_super_admin->save();

// ROLE SUPER ADMIN
$role_super_admin = Role::where('role', 'ROLE_SUPER_ADMIN')->first();
$role_super_admin->permissions()->sync([7, 8, 9]);
$role_super_admin->save();