<?php

namespace App\Controller;

use App\Core\Controller;

class AppController extends Controller
{
	public function index()
	{
		return $this->render('app/index.html.twig');
	}
}