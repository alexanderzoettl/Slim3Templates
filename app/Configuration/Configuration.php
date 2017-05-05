<?php

namespace App\Configuration;

class Configuration{

	public static $smtp = [
			'host' => 'vweb06.nitrado.net',
			'port' => 25,
			'username' => 'mailer@xeroserver.org',
			'password' => 'mailer123;',
			'mail' => 'mailer@xeroserver.org',
			'name' => 'PHPMailer',
	];

	public static $db = [
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'SlimTemplate',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci'
	];

	public static $enableEmailActivation = false;

}