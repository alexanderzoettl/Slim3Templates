<?php

use Respect\Validation\Validator as v;
use App\Configuration\Configuration as conf;

session_start();

//Ladet die von Composer installierten Packete (Slim3, Twig, ...)
require __DIR__ . '/../vendor/autoload.php';

//Slim App Instanz
$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'determineRouteBeforeAppMiddleware' => true,
		'addContentLengthHeader' => false,
		'db' => conf::$db
	]

]);

//Container der Instanziierten App
$container = $app->getContainer();

//Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
	return $capsule;
};

//Auth
$container['auth'] = function($container){
	return new \App\Auth\Auth($container);
};

//Flash
$container['flash'] = function($container){
	return new \Slim\Flash\Messages();
};

//Twig
$container['view'] = function ($container){

	//Mgacht eine Twig Instanz und gibt den Pfad mit den views an
	$view = new Slim\Views\Twig(__DIR__ . '/../resources/views',
			[
			//Cachgin soll beim Entwickeln aus sein
			'cache' => false
			]
	);

	//Added eine TwigExtentsion zu unserer Twig instanz
	$view->addExtension(new Slim\Views\TwigExtension(
			$container->router,
			$container->request->getUri()
		));

	$view->getEnvironment()->addGlobal('auth',[
		'check' =>  $container->auth->check(),
		'user' =>  $container->auth->user(),
	]);

	$view->getEnvironment()->addGlobal('flash',$container->flash);

	//Setzt das fertige Twig Objekt als container an der Stelle 'view'
	return $view;
};

//Valiation
$container['validator'] = function ($container) {
	return new App\Validation\Validator;
};

// === Controllers ===

$container['StaticPageController'] = function($container){
	return new \App\Controllers\StaticPageController($container);
};

$container['AuthController'] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};

//CSRF
$container['csrf'] = function($container){
	return new \Slim\Csrf\Guard;
};

//MAIL
$container['mailer'] = function($container){
	return new \App\Mail\Mailer($container->view);
};



//Middlware
$app->add(new \App\Middleware\NavbarLocationMiddleware($container));
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

//Custom Validation Rules
v::with('App\\Validation\\Rules\\');

//Routes
require __DIR__ . '/../app/routes.php';