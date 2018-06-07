<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('upload', function($table){
	$table->increments('id');
	$table->string('type');
	$table->string('image');
	$table->integer('user_id');
	$table->softDeletes();	
	$table->timestamps();
});