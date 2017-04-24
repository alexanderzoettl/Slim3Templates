<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	//Tablename
	protected $table = 'users';	
	
	//Changable Columns
	protected $fillable = [
		'email', 'name', 'surname', 'hash'
	];

	//Adds a new user to the users table
	public static function Add($email, $hash, $name, $surname){
		User::create([
			'name' => $name,
			'surname' => $surname,
			'hash' => $hash,
			'email' => $email,
		]);
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
}