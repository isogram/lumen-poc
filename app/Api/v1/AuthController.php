<?php

namespace App\Api\v1;

use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Transformers\PartnerUserTransformer;
use App\Models\PartnerUser;

class AuthController extends ApiController
{

    use Helpers;

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        // set rules
        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];

        // set messages
        $messages = [
            'username.required' => ':attribute is required',
            'password.required' => ':attribute is required'
        ];

        // make validator
        $validator = Validator::make($request->all(), $rules, $messages);

        // if validator failed
        if ($validator->fails()) {

            $errorKeys = $validator->messages()->keys();

            $errors = [];

            foreach ($errorKeys as $key) {

                $errors[] = [
                    'key'       => $key,
                    'message'   => $validator->messages()->first($key)
                ];

            }

            $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
            $this->setMessage('validation failed');
            $this->setError($errors);

            return $this->respond(null);
        }

        // get credentials request
        $credentials = $this->getCredentials($request);

        try {

            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {

                $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED);
                $this->setMessage('invalid credentials');

                return $this->respond(null);

            }

        } catch (JWTException $e) {

            // Something went wrong whilst attempting to encode the token
            $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR);
            $this->setMessage('could not create token');

            return $this->respond(null);
        }

        // All good so return the token

        $data = [
            'token' => $token
        ];

        $this->setStatusCode(IlluminateResponse::HTTP_OK);
        $this->setMessage('login success');

        return $this->respond($data);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    /**
     * Invalidate a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvalidate()
    {
        $token = JWTAuth::parseToken();

        $token->invalidate();

        return ['success' => 'token_invalidated'];
    }
}
