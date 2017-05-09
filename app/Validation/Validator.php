<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;


class Validator{

	//Error messages
	protected $errors;


	//Function to get the first not empty error message
	protected function getFirstErrorMessage($array){
	  foreach($array as $v){
	    if($v !== ""){
	        return $v;
	    }
	  }
	  return null;
	}


	public function validate($request, array $rules){

		//For each defined rule check if the value passes
		foreach ($rules as $field => $rule) {

			try {

				$rule->setName(ucfirst($field))->assert($request->getParam($field));

			} catch (NestedValidationException $e) {
				//Catch the errors for each field
				$this->errors[$field] = $this->getFirstErrorMessage($e->findMessages([
	    			'notEmpty' => 'Das Feld darf nicht leer sein!',
	    			'email' => 'Es muss eine gültige Emailaddresse eingegeben werden!',
	    			'alpha' => '{{name}} darf nur aus Buchstaben bestehen!',
    				'noWhitespace' => 'Das Feld darf keine Leerzeichen enthalten!',
    				'matchesConfirmPassword' => 'Passwörter müssen übereinstimmen!',
    				'emailAvailable' => 'Email existiert bereits!',
    				'noWhitespace' => 'Keine Leerzeichen erlaubt!',
    				'matchesUserPassword' => 'Altes Passwort ist falsch!',
    			]));

			}
		}

		$_SESSION['errors'] = $this->errors;

		return $this;
	}

	public function failed(){
		//If errors is empty
		return !empty($this->errors);
	}

}