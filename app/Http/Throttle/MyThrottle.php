<?php

namespace App\Http\Throttle;

use Auth;
use Illuminate\Container\Container;
use Dingo\Api\Http\RateLimit\Throttle\Throttle;

class MyThrottle extends Throttle
{
    /**
     * Array of throttle options.
     *
     * @var array
     */
    protected $options = ['limit' => 250, 'expires' => 10];

    /**
     * Create a new throttle instance.
     *
     * @param array $options
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function match(Container $app)
    {
        // Perform some logic here and return either true or false depending on whether
        // your conditions matched for the throttle.

        // return true;
    }
}