<?php

namespace App\Controllers\Auth;

use \App\Controllers\Controller;
use \App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{

	//========= Forgot Password ==========//

	public function getForgotPassword($request, $response){

		if($code = $request->getParam('code')){
			//Check code get user and display change password shit
			if(User::where('forgot_hash', $code)->count() === 1){
					return $this->view->render($response, 'auth/forgot_change.twig', ['code' => $code]);
			}else{
				$this->flash->addMessage('danger', 'Ungültiger Code!');
				return $response->withRedirect($this->router->pathFor('auth.forgot'));
			}
			//return forgot_change

		}else{
			//Display email shit
			return $this->view->render($response, 'auth/forgot_email.twig');
		}

	}

	public function postForgotPassword($request, $response){
		
		if($request->getParam('type') == 'email'){
			//Validate email
			$validation = $this->validator->validate($request, [
				'email' => v::noWhitespace()->notEmpty()->email(),
			]);

			if($validation->failed()){
				return $response->withRedirect($this->router->pathFor('auth.forgot'));
			}
			//Check if exists
			$email = $request->getParam('email');
			if($user = User::GetByEmail($email)){
				//Generate link_hash
				$hash = uniqid();
				$user->setForgotHash($hash);
				//send Email
				$mail = $this->mailer->sendForgotPasswordMail($user);
				//If mail send failed
				if(!$mail){
					$this->flash->addMessage('danger', 'Email sending failed!');
					return $response->withRedirect($this->router->pathFor('auth.forgot'));	
				}

			}
			//refresh and notify user
			$this->flash->addMessage('success', 'Wenn benutzer existiert wurde reset mail verschickt!');
			return $response->withRedirect($this->router->pathFor('auth.forgot'));

		}else{

			$code = $request->getParam('code');

			//validate passwords
			$validation = $this->validator->validate($request, [
				'password' => v::noWhitespace()->notEmpty()->matchesConfirmPassword($request->getParam('confirm_password')),
				'confirm_password' => v::noWhitespace()->notEmpty()
			]);

			if($validation->failed()){
				return $response->withRedirect($this->router->pathFor('auth.forgot'). '?code=' . $code);
			}

			//check hidden field link
			if(User::where('forgot_hash', $code)->count() === 1){
				$user = User::where('forgot_hash', $code)->first();
				$user->setPassword($request->getParam('password'));
				$user->setForgotHash('');
				$this->flash->addMessage('success', 'Password wurde aktualisiert!');
				return $response->withRedirect($this->router->pathFor('auth.signin'));
			}

			$this->flash->addMessage('danger', 'Code missmatch error!');
			return $response->withRedirect($this->router->pathFor('auth.forgot'). '?code=' . $code);

	
		}
	}


	//========= ACTIVATE ACCOUNT ==========//
	public function getActivateAccount($request, $response){
		$code = $request->getParam('code');

		$user = User::where('activation_hash' , $code)->first();
		if($user){
			$user->activate();
			$this->flash->addMessage('success', 'Activation Successfull!');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		$this->flash->addMessage('danger', 'Wrong activation code!');
		return $response->withRedirect($this->router->pathFor('auth.signin'));
		
	}


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

		//If not activated
		if(!$this->auth->user()->activated){
			$this->auth->logout();
			$this->flash->addMessage('danger', 'Bestätigungsmail wurde nicht bestätigt! Im Spam Ordner nachsehen!');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}


		$this->flash->addMessage('success', 'Sie wurden eingeloggt!');
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

		//If Mail fails, activate user
		if (!$this->mailer->sendActivationMail($user)){

			$this->flash->addMessage('success', 'Sie wurden erfolgreich registriert!');
			$user->activate();

		}else
			$this->flash->addMessage('warning', 'Sie wurden erfolgreich registriert! Bitte bestätigen Sie die Aktivierungsmail!');


		return $response->withRedirect($this->router->pathFor('auth.signin'));

	}
}