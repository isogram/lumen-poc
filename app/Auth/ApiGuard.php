<?php

namespace App\Auth;

use Cache;
use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;

class ApiGuard extends TokenGuard
{

    protected $storageKey;
    protected $cacheKey;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->storageKey = 'username';
        $this->cacheKey = 'credentials:';
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        return $this->validate($credentials);
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function onceUsingId($id)
    {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            $this->setUser($user);

            return true;
        }

        return false;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if ($user = $this->provider->retrieveByCredentials($credentials)) {
            return $this->provider->validateCredentials($user, $credentials);
        }

        return false;
    }

    /**
     * Get the token for the current request.
     * Force token from Authorization Bearer
     *
     * @return string
     */
    protected function getTokenForRequest()
    {
        $token = $this->request->input($this->inputKey);

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        return $token;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.

        $credentials = $this->getTokenForRequest();

        if ($user = Cache::get($this->cacheKey . $credentials)) {
            return $user;
        }

        if (! empty($credentials)) {
            if ($user = $this->provider->retrieveByCredentials([$this->storageKey => $credentials])) {

                // set cache for 60 minutes
                Cache::put($this->cacheKey . $credentials, $user, 60);

                return $this->user = $user;
            }
        }
    }
}
