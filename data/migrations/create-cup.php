<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('cups', function($table){
	$table->increments('id');
	$table->integer('user_id');
	$table->integer('image_id');
	$table->string('description');
	$table->softDeletes();	
	$table->timestamps();
});