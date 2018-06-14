<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('nach', function($table){
	$table->increments('id');
	$table->string('title');
	$table->string('description');
	$table->integer('message_id');
	$table->integer('user_id')->nullable();
	$table->integer('author');
	$table->timestamps();
});