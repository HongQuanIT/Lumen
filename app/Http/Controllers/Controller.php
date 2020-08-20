<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Log;

class Controller extends BaseController
{
    const STATUS_SUCCESS = true;
    const STATUS_FAILED = false;
    
    private $_statusCode = 200; // HTTP RESPONSE STATUS CODE, DEFAULT = 200
    private $_status;
    private $_code;
    private $_message;
    private $_data;
    private $_errors = [];

    /**
     * @param $code
     *
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->_statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $code
     *
     * @return $this
     */
    protected function setCode($code)
    {
        $this->_code = $code;

        return $this;
    }

    /**
     * @param $status
     *
     * @return $this
     */
    protected function setStatus($status)
    {
        $this->_status = $status;

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    protected function setMessage($message)
    {
        $this->_message = $message;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    protected function setData($data)
    {
        $this->_data = $data;

        return $this;
    }

    protected function setErrors($error)
    {
        $this->_errors = $error;

        return $this;
    }

    /**
     * @return Log
     */
    public function getLogger()
    {
        return Log::channel('api');
    }

    /***
     * End point return data
     *
     * @return JsonResponse
     */
    public function response()
    {
        // Prepare return data
        $data = [
            'status' => $this->_status,
            'code' => $this->_code,
            'message' => $this->_message,
            'data' => $this->_data,
            'errors' => $this->_errors,
        ];

        return response()->json($data, $this->_statusCode);
    }

    public function sendSuccessData($data = null, $message = null)
    {
        return $this->setStatus(self::STATUS_SUCCESS)
            ->setMessage($message)
            ->setCode(200)
            ->setData($data)
            ->response();
    }

    public function sendErrorData($code = null)
    {
        if ($code) {
            $this->setCode($code);
        }

        return $this->setStatus(self::STATUS_FAILED)
            ->response();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => true,
            'code' => 200,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function hq_exceptions($exception)
    {
        $this->getLogger()->error($exception);
        if($exception instanceof ValidationException){
            $array_message = $exception->errors();
            $status_code = $exception->status;
            $message = '';
            foreach ($array_message as $value){
                $message .=  $value[0].'. ';
             }
            return $this->setErrors($array_message)->setMessage($message)->sendErrorData()->setStatusCode($status_code)->setCode($status_code);
        }
        return $this->setMessage($exception->getMessage())->setStatusCode(422)->setCode(422)->sendErrorData();
    }


}
