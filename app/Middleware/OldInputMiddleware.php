<?php

namespace App\Middleware;

class OldInputMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		
		//If it exists
		if(isset($_SESSION['old'])){
			//Set it as global for twig to use
			$this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
		}
		
		//Set the current input as old input
		$_SESSION['old'] = $request->getParams();
		
		$response = $next($request, $response);
		return $response;
	}

}
