<?php

// composer autoload
require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;
use App\Core\ServiceProvider;

$container = new ServiceProvider();
$container->get()['db'];

Capsule::schema()->create('contents', function($table){
	$table->increments('id');
	$table->string('type');
	$table->string('title');
	$table->text('article')->nullable();
	$table->integer('user_id');
	$table->softDeletes();	
	$table->timestamps();
});