<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('rates', function($table){
	$table->increments('id');
	$table->string('message_id');
	$table->string('author');
	$table->string('user');
	$table->text('sign');
	$table->softDeletes();
	$table->timestamps();
});