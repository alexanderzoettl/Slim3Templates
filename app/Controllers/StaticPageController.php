<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class StaticPageController extends Controller
{

	public function home($request, $response)
	{		
		return $this->view->render($response, 'home.twig');
	}
	
	public function about($request, $response)
	{
		return $this->view->render($response, 'about.twig');
	}

	public function impressum($request, $response)
	{
		return $this->view->render($response, 'impressum.twig');
	}
}