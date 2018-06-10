<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('activities', function($table){
	$table->increments('id');
	$table->integer('user_id');
	$table->string('ip')->nullable();
	$table->string('userAgent')->nullable();
	$table->string('lastPage')->nullable();
	$table->timestamps();
});