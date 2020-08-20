<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Exception;

class UserController extends Controller 
{
    protected $service;
    #**************************************************************************
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function getAllUser(){
        try {
            return $this->sendSuccessData($this->service->getAllUser(),"List user data.");
        }catch (Exception $exception) {
            return $this->hq_exceptions($exception);
        }
    }
}