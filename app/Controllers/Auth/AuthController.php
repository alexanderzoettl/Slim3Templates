<?php

namespace App\Controllers\Auth;

use \App\Controllers\Controller;
use \App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{

	public function getSignIn($request, $response){
		return $this->view->render($response, 'auth/signin.twig');
	}

	public function postSignIn($request, $response){

		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email(),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		//On Validation error, return to Signin and display errors
		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if(!$auth)
			return $response->withRedirect($this->router->pathFor('auth.signin'));

		return $response->withRedirect($this->router->pathFor('home'));

	}

	//Get Request Signup
	public function getSignUp($request, $response){
		return $this->view->render($response, 'auth/signup.twig');
	}

	//Post Request Signup
	public function postSignUp($request, $response){

		//Validation Rules
		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'name' => v::noWhitespace()->notEmpty()->alpha(),
			'surname' => v::noWhitespace()->notEmpty()->alpha(),
			'password' => v::notEmpty()->noWhitespace()->matchesConfirmPassword($request->getParam('confirm_password')),
			'confirm_password' => v::notEmpty(),

		]);

		//On Validation error, return to Signup and display errors
		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}


		//No erros: Add User to Table
		$user = User::Add(
			$request->getParam('email'),
			password_hash($request->getParam('password'), PASSWORD_DEFAULT),
			$request->getParam('name'),
			$request->getParam('surname')
		);

		//Redirect to HomePage
		return $response->withRedirect($this->router->pathFor('home'));
	}
}