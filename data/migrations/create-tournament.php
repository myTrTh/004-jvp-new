<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('tournaments', function($table){
	$table->increments('id');
	$table->string('title');
	$table->integer('user_id');
	$table->integer('status');
	$table->integer('type')->nullable();
	$table->string('image')->nullable();
	$table->string('info')->nullable();
	$table->string('access')->nullable();
	$table->timestamp('completed')->nullable();
	$table->softDeletes();	
	$table->timestamps();
});