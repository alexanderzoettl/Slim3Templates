<?php

namespace App\Middleware;

class NavbarLocationMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		
		//TODO: Preventing null pointer exception when calling unknown routes
		$name = $request->getAttribute('route')->getName();

		if($name)
			$this->container->view->getEnvironment()->addGlobal('routeName',$name );
		
		$response = $next($request, $response);
		return $response;
	}


}