<?php


namespace App\Auth;

use App\Models\User;

class Auth{

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