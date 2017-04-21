<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		
		//If there were errors
		if(isset($_SESSION['errors'])){
			//Set them as global for twig to use
			$this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
			//And reset them
			unset($_SESSION['errors']);
		}
		
		$response = $next($request, $response);
		return $response;
	}

}
