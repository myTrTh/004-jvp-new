<?php

namespace App\Utils;

use App\Core\View;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_SpoolTransport;
use Swift_FileSpool;
use Swift_MemorySpool;
use App\Application;

class Mailer
{
	private $container;
	private $mailer;
	private $transport;
	private $smtp_transport;
	private $spool;
	private $view;

	public function __construct($container)
	{
		$this->container = $container;
  		// $this->transport = new Swift_SpoolTransport(new Swift_FileSpool($this->container['email_connection']['spool']));

  		$this->spool = new Swift_MemorySpool();

		$this->smtp_transport = (new Swift_SmtpTransport(
			$this->container['email_connection']['host'],
			$this->container['email_connection']['port'],
			$this->container['email_connection']['encryption']))
  				->setUsername($this->container['email_connection']['user'])
  				->setPassword($this->container['email_connection']['password']);

  		$this->spool->flushQueue($this->smtp_transport);

		// send messeges with SPOOL
		// $this->mailer = new Swift_Mailer($this->transport);

		// send messeges without spool
		$this->mailer = new Swift_Mailer($this->smtp_transport);

		$this->view = new View($container);
	}

	public function send(string $subject, string $to, string $template, $data = null)
	{
		$message = (new Swift_Message($subject))
	  			 ->setFrom([$this->container['email_connection']['from'] => $this->container['email_connection']['from_name']])
	  			 ->setTo($to)
	  			 ->setBody($this->view->render($template, 
	  			 	array("data" => $data)), "text/html");
	  	$this->mailer->send($message);
  	}
}