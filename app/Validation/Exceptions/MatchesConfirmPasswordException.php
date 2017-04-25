<?php

namespace App\Validation\Exceptions;
use Respect\Validation\Exceptions\ValidationException;

class MatchesConfirmPasswordException extends ValidationException{

	public static $defaultTemplates = [
		self::MODE_DEFAULT => [
			self::STANDARD => 'Passwords do not match!',
		],
	];
}
