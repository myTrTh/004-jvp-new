<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('reset_passwords', function($table){
	$table->increments('id');
	$table->string('user_id');
	$table->boolean('status');	
	$table->string('confirmation_token')->nullable();
	$table->dateTime('confirmation_datetime')->nullable();
	$table->softDeletes();	
	$table->timestamps();
});