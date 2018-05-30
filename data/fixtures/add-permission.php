<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;
use App\Model\Permission;

$container = new ServiceProvider();
$container->get()['db'];

// USER PERMISSIONS
$permission1 = new Permission();
$permission1->permission = 'guestbook-write';
$permission1->save();

$permission12 = new Permission();
$permission12->permission = 'vote-use';
$permission12->save();

// MODERATOR PERMISSIONS
$permission2 = new Permission();
$permission2->permission = 'content-control-all';
$permission2->save();

$permission3 = new Permission();
$permission3->permission = 'content-control-own';
$permission3->save();

$permission4 = new Permission();
$permission4->permission = 'guestbook-delete-all';
$permission4->save();

$permission5 = new Permission();
$permission5->permission = 'role-control-user';
$permission5->save();

$permission13 = new Permission();
$permission13->permission = 'vote-control-own';
$permission13->save();

$permission14 = new Permission();
$permission14->permission = 'vote-control-all';
$permission14->save();

// ADMIN PERMISSIONS

$permission6 = new Permission();
$permission6->permission = 'role-control-moderator';
$permission6->save();

// SUPER ADMIN PERMISSIONS
$permission7 = new Permission();
$permission7->permission = 'role-control';
$permission7->save();

$permission8 = new Permission();
$permission8->permission = 'permission-control';
$permission8->save();

$permission9 = new Permission();
$permission9->permission = 'role-control-admin';
$permission9->save();

// TOURNAMENT PERMISSIONS
$permission10 = new Permission();
$permission10->permission = 'tournament-control';
$permission10->save();

$permission11 = new Permission();
$permission11->permission = 'tour-control';
$permission11->save();