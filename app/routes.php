<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

//Für alle
$app->get('/', 'StaticPageController:home')->setName('home');
$app->get('/about', 'StaticPageController:about')->setName('about');
$app->get('/impressum', 'StaticPageController:impressum')->setName('impressum');


//Nur für nicht eingeloggte
$app->group('', function() {

	//Authentication (Signup, Signin)
	$this->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
	$this->post('/signup', 'AuthController:postSignUp');

	$this->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
	$this->post('/signin', 'AuthController:postSignIn');

	$this->get('/activate', 'AuthController:getActivateAccount');


})->add(new GuestMiddleware($container));




//Nur für nicht eingeloggte
$app->group('', function() {

	$this->get('/logout', 'AuthController:getLogout')->setName('auth.logout');
	$this->get('/change', 'AuthController:getChangePassword')->setName('auth.change');
	$this->post('/change', 'AuthController:postChangePassword');

})->add(new AuthMiddleware($container));

