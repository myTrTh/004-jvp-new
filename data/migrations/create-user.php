<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('users', function($table){
	$table->increments('id');
	$table->string('username')->unique();
	$table->string('email')->unique();
	$table->string('password');
	$table->string('image')->nullable();
	$table->boolean('is_active');	
	$table->text('options')->nullable();
	$table->string('confirmation_token')->nullable();
	$table->dateTime('confirmation_datetime')->nullable();	
	$table->softDeletes();	
	$table->timestamps();
});