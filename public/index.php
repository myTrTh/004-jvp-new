<?php
// $start_time = microtime(true);

// require composer autoload
require_once __DIR__.'/../vendor/autoload.php';

use App\Application;
use App\Core\ServiceProvider;

try {

	// START DEV VERSION
	$env = $_GET['env'] ?? '';
	// STOP DEV VERSION

	$container = new ServiceProvider($env);

	$app = new Application($container->get());
	$app->run();

} catch (Throwable $t) {
	
	echo $t->getMessage();
	echo '<br>'.$t->getFile().' on line: '.$t->getLine();
}

# write time in log
// $timer = microtime(true) - $start_time;
// $speedmsg = "time: ".$timer." ms, page: ".$_SERVER['REQUEST_URI']." \n";
// error_log($speedmsg, 3, __DIR__.'/../var/log/speed.log');