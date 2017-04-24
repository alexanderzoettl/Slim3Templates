<?php


namespace App\Auth;

use App\Models\User;

class Auth{


	public function signedIn(){
		return isset($_SESSION['user']);
	}

	public function attempt($email, $password){

		$user = User::getByEmail($email);

		if(!$user)
			return false;

		if (password_verify($password, $user->hash)){

			$_SESSION['user'] = $user->id;

			return true;
		}

		return false;

	}
}