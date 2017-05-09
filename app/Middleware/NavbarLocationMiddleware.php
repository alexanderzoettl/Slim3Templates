<?php

namespace App\Middleware;

class NavbarLocationMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		
		//Prevents crashes
		if(!$request->getAttribute('route')){
			throw new \Slim\Exception\NotFoundException($request, $response);
		}

		$name = $request->getAttribute('route')->getName();

		if($name)
			$this->container->view->getEnvironment()->addGlobal('routeName',$name );
		
		$response = $next($request, $response);
		return $response;
	}


}