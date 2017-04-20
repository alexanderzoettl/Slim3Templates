<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class AboutController extends Controller
{

	public function index($request, $response)
	{
		
		echo "Alex";

		return $this->view->render($response, 'about.twig');
	}
}