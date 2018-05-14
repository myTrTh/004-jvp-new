<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('permission_user', function($table){
	$table->increments('id');
	$table->integer('permission_id');
	$table->integer('user_id');
	$table->timestamps();
});