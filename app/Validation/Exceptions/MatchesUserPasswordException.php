<?php

namespace App\Validation\Exceptions;
use Respect\Validation\Exceptions\ValidationException;

class MatchesUserPasswordException extends ValidationException{

	public static $defaultTemplates = [
		self::MODE_DEFAULT => [
			self::STANDARD => 'Passwords do not match!',
		],
	];
}
