<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	//Tablename
	protected $table = 'users';	
	
	//Changable Columns
	protected $fillable = [
		'email', 'name', 'surname', 'hash', 'activation_hash', 'activated', 'forgot_hash'
	];

	//Adds a new user to the users table
	public static function Add($email, $password, $name, $surname){
		$user = User::create([
			'name' => $name,
			'surname' => $surname,
			'email' => $email,
			'activation_hash' => uniqid(),
			'activated' => false
		]);
		$user->setPassword($password);
		return $user;

	}

	//Gets the user by id
	public static function GetByID($id){
		return User::where('id' , $id)->first();
	}

	//Gets the user by email
	public static function GetByEmail($email){
		return User::where('email' , $email)->first();
	}

	public function setPassword($password){
		$this->update([
			'hash' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}

	public function activate(){
		$this->update([
			'activation_hash' => '',
			'activated' => true
		]);
	}

	public function setForgotHash($hash){
		$this->update([
			'forgot_hash' => $hash
		]);
	}
}