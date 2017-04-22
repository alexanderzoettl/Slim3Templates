<?php

namespace App\Validation\Rules;
use Respect\Validation\Rules\AbstractRule;

class MatchesConfirmPassword extends AbstractRule{

	protected $check;

	public function __construct($check){
		$this->check = $check;		
	}

	public function validate($input){
		return $input === ($this->check);
	}
} 