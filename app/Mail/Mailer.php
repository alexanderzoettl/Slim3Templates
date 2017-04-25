<?php

namespace App\Mail;

class Mailer{

	protected $mailer;
	protected $view;

	public function __construct($view){
		$this->view = $view;
		$this->mailer = new \PHPMailer;
		$this->mailer->IsSMTP();
		$this->mailer->Host = 'vweb06.nitrado.net';
		$this->mailer->SMTPAuth = true;
		$this->mailer->Port = 25;
		$this->mailer->Username = 'mailer@xeroserver.org';
		$this->mailer->Password = 'mailer123;';
		$this->mailer->isHTML(true);
		$this->mailer->From = "mailer@xeroserver.org";
		$this->mailer->FromName = "PHPMailer";
	}

	public function send($twig, $data, $subject, $email, $name){
		$this->mailer->addAddress($email, $name);
		$this->mailer->Subject = $subject;
        $this->mailer->Body = $this->view->fetch($twig, $data);
		return $this->mailer->send();
	}

	public function sendActivationMail($user){

		return $this->send(
			'templates/mail/activationMail.twig',
			['activation_hash' => $user->activation_hash ],
			'Activation Mail',
			$user->email,
			$user->name . ' ' . $user->surname
		);
	}

}