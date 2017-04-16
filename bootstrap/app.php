<?php

session_start();

//Ladet die von Composer installierten Packete (Slim3, Twig, ...)
require __DIR__ . '/../vendor/autoload.php';

//Slim App Instanz
$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true
	]

]);

//Container der Instanziierten App
$container = $app->getContainer();


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

$container['HomeController'] = function($container){
	return new \App\Controllers\HomeController($container);
};

require __DIR__ . '/../app/routes.php';