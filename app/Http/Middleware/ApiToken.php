<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;

class ApiToken
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->isTokenMatched($request->get('token'))){
            return $this->res($request->all(), 'Please submit api token', 403);
        }

        return $next($request);
    }

    public static function get(){
        /**
         * when device send request, build token
         * md5(time(in minute) + KEY)
         */
        /** token changed in 24=hr */
        $roundInDate = ceil(time() / 60 / 60 / 24);
        $key = env("APP_KEY");
        return md5("{$roundInDate}+{$key}");
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isTokenMatched($token){
        if(empty($token) || $token != $this->get()){
            return false;
        }

        return true;
    }
}
