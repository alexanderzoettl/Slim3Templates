<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class HomeController extends Controller
{

	public function index($request, $response)
	{
		
		$user = $this->db->table('users')->find(1);
		var_dump($user->email);

		return $this->view->render($response, 'home.twig');
	}
}