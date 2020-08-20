<?php

namespace App\Services;
use App\User;
class UserService extends BaseService
{
    public function getAllUser()
    {
        //Validate_Exception(["heheh"],500);// Custom validate exception.
        return User::all();
    }   
}