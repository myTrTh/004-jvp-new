<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\Permission;

$container = new ServiceProvider();
$container->get()['db'];

// USER PERMISSIONS 1 2 3 4
$p = new Permission();
$p->permission = 'guestbook-write';
$p->save();

$p = new Permission();
$p->permission = 'vote-control-own';
$p->save();

$p = new Permission();
$p->permission = 'rate-action';
$p->save();

$p = new Permission();
$p->permission = 'vote-use';
$p->save();

// MODERATOR PERMISSIONS 5 6 7 8 9
$p = new Permission();
$p->permission = 'content-control-all';
$p->save();

$p = new Permission();
$p->permission = 'content-control-own';
$p->save();

$p = new Permission();
$p->permission = 'guestbook-delete-all';
$p->save();

$p = new Permission();
$p->permission = 'role-control-user';
$p->save();

$p = new Permission();
$p->permission = 'vote-control-all';
$p->save();

// ADMIN PERMISSIONS 10 11 12

$p = new Permission();
$p->permission = 'role-control-moderator';
$p->save();

$p = new Permission();
$p->permission = 'tournament-control';
$p->save();

$p = new Permission();
$p->permission = 'tour-control';
$p->save();

// SUPER ADMIN PERMISSIONS 13 14 15
$p = new Permission();
$p->permission = 'role-control';
$p->save();

$p = new Permission();
$p->permission = 'permission-control';
$p->save();

$p = new Permission();
$p->permission = 'role-control-admin';
$p->save();
