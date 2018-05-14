<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('permissions', function($table){
	$table->increments('id');
	$table->string('permission');
	$table->string('description')->nullable();
	$table->timestamps();
});