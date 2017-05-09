<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use Respect\Validation\Validator as v;

class FormController extends Controller
{

	public function get($request, $response)
	{
		return $this->view->render($response, 'exampleForm.twig');
	}

	public function post($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'field1' => v::notEmpty(),
			'field2' => v::notEmpty(),
			'field3' => v::notEmpty(),
		]);

		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('exampleForm'));
		}

		$this->flash->addMessage('success', 'Post successfull!');
		return $response->withRedirect($this->router->pathFor('exampleForm'));
	}
}