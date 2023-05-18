<?php

require 'bootstrap.php';

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['email', 'password'];

    protected $hidden = ['password'];

    public $timestamps = true;

    public function verifyPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}
