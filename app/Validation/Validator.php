<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;


class Validator{

	//Error messages
	protected $errors;

	public function validate($request, array $rules){

		//For each defined rule check if the value passes
		foreach ($rules as $field => $rule) {

			try {

				$rule->setName(ucfirst($field))->assert($request->getParam($field));

			} catch (NestedValidationException $e) {
				//Catch the errors for each field
				$this->errors[$field] = $e->getMessages();
			}
		}
		return $this;
	}

	public function failed(){
		//If errors is empty
		return !empty($this->errors);
	}

}