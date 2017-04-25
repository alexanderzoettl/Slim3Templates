<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class StaticPageController extends Controller
{

	public function home($request, $response)
	{		

		$res = $this->mailer->send('templates/mail.twig', ['test' => 'HALLOTEST', 'test2' => 'HALLOTEST2'] , function($message){
      $message->to('xeroaustria@hotmail.com');
      $message->subject('Email Subject');
      $message->from('mailer@xeroserver.org'); // if you want different sender email in mailer call function
      $message->fromName('Sender Name'); // if you want different sender name in mailer call function
	});
		var_dump($res);
		die;

		return $this->view->render($response, 'home.twig');
	}
	
	public function about($request, $response)
	{
		return $this->view->render($response, 'about.twig');
	}

	public function impressum($request, $response)
	{
		return $this->view->render($response, 'impressum.twig');
	}
}