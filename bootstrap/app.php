<?php

session_start();

//Ladet die von Composer installierten Packete (Slim3, Twig, ...)
require __DIR__ . '/../vendor/autoload.php';

//Slim App Instanz
$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'determineRouteBeforeAppMiddleware' => true,
		'addContentLengthHeader' => false,
		'db' => [
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'kfm2.0',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci'
		]
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

//Middlware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));

//Routes
require __DIR__ . '/../app/routes.php';