<?php

namespace App\Core;

use App\Application;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

class Session
{
	private $config;

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function activate()
	{
		$storage = new NativeSessionStorage(array(
			'cookie_lifetime' => $this->config['session']['cookie_lifetime'],
			'gc_maxlifetime' => $this->config['session']['gc_maxlifetime'], 
			'cookie_httponly' => $this->config['session']['cookie_httponly'],
			'gc_probability' => $this->config['session']['gc_probability'],
			'gc_divisor' => $this->config['session']['gc_divisor']
		), new NativeFileSessionHandler($this->config['session']['save_path']));
		
		$session = new SymfonySession($storage);
		$session->start();
	}
}