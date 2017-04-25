<?php

namespace App\Controllers\Auth;

use \App\Controllers\Controller;
use \App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{

	//========== CHANGE PASSWORD ==========//
	public function getChangePassword($request, $response){
		return $this->view->render($response, 'auth/change.twig');
	}

	public function postChangePassword($request, $response){

		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhitespace()->notEmpty()->matchesUserPassword($this->auth->user()->hash),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.change'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));
		$this->flash->addMessage('success', 'Passwort geändert! Bitte neu anmelden!');
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('auth.signin'));
	}

	//========== LOGOUT ==================//
	public function getLogout($request, $response){
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('home'));
	}

	//========== SIGN IN ================//
	public function getSignIn($request, $response){
		return $this->view->render($response, 'auth/signin.twig');
	}

	public function postSignIn($request, $response){

		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email(),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		$auth = $this->auth->login(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if(!$auth){
			$this->flash->addMessage('danger', 'Falsche Zugansdaten!');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		return $response->withRedirect($this->router->pathFor('home'));

	}

	//========== SIGN OUT ==============//
	public function getSignUp($request, $response){
		return $this->view->render($response, 'auth/signup.twig');
	}

	public function postSignUp($request, $response){

		$validation = $this->validator->validate($request, [
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'name' => v::noWhitespace()->notEmpty()->alpha(),
			'surname' => v::noWhitespace()->notEmpty()->alpha(),
			'password' => v::notEmpty()->noWhitespace()->matchesConfirmPassword($request->getParam('confirm_password')),
			'confirm_password' => v::notEmpty(),
		]);

		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$user = User::Add(
			$request->getParam('email'),
			$request->getParam('password'),
			$request->getParam('name'),
			$request->getParam('surname')
		);

		$this->flash->addMessage('success', 'Sie wurden erfolgreich registriert!');

		return $response->withRedirect($this->router->pathFor('home'));
	}
}