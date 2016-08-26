<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;

class ApiController extends Controller {

    protected $statusCode   = IlluminateResponse::HTTP_OK;
    protected $message      = null;
    protected $error        = null;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    public function respond($data, $headers = [])
    {
        $resp = [
            'message'       => $this->getMessage(),
            'status_code'   => $this->getStatusCode(),
            'data'          => $data,
            'error'         => $this->getError(),
        ];

        return response()->json($resp, $this->getStatusCode(), $headers);
    }

}