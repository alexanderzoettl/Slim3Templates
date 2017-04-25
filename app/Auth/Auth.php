<?php


namespace App\Auth;

use App\Models\User;

class Auth{


	public function logout(){
		unset($_SESSION['user']);
	}

	public function user(){
		if(isset($_SESSION['user'])){
			return User::find($_SESSION['user']);
		}
	}

	public function check(){
		return isset($_SESSION['user']);
	}

	public function login($email, $password){

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