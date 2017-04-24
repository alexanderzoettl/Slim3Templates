<?php

namespace App\Middleware;

class NavbarLocationMiddleware extends Middleware{

	public function __invoke($request, $response, $next){

		$this->container->view->getEnvironment()->addGlobal('routeName', $request->getAttribute('route')->getName());
		
		$response = $next($request, $response);
		return $response;
	}


}