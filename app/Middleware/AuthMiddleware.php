<?php

namespace App\Middleware;
use App\Configuration\Configuration as conf;

class AuthMiddleware extends Middleware{
	
	public function __invoke($request, $response, $next){

		//Add config
		$this->container->view->getEnvironment()->addGlobal('enableEmailActivation', conf::$enableEmailActivation);



		if (!$this->container->auth->check()){
			$this->container->flash->addMessage('danger', 'Bitte einloggen!');
			return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		}
		
		$response = $next($request, $response);
		return $response;
	}
}