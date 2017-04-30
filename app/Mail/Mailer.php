<?php

namespace App\Mail;

use App\Configuration\Configuration as conf;

class Mailer{

	protected $mailer;
	protected $view;

	public function __construct($view){

		$this->view = $view;
		$this->mailer = new \PHPMailer;
		$this->mailer->IsSMTP();
		$this->mailer->Host = conf::$smtp['host'];
		$this->mailer->SMTPAuth = true;
		$this->mailer->Port = conf::$smtp['port'];
		$this->mailer->Username = conf::$smtp['username'];
		$this->mailer->Password = conf::$smtp['password'];
		$this->mailer->isHTML(true);
		$this->mailer->From = conf::$smtp['mail'];
		$this->mailer->FromName = conf::$smtp['name'];
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

	public function sendForgotPasswordMail($user){

		return $this->send(
			'templates/mail/forgotMail.twig',
			['forgot_hash' => $user->forgot_hash ],
			'Password Reset Mail',
			$user->email,
			$user->name . ' ' . $user->surname
		);
	}

}