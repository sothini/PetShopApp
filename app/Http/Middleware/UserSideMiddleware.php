<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use App\Services\JwtService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;



class UserSideMiddleware
{

    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $service = new JwtService();

            if ($bearerToken = $service->extractBearerToken($request)) 
            { 
                if(!$service->validate($bearerToken)){
                    return $this->errorResponse("Unauthorised access",401);
                }
            } 
            else 
            {
                return $this->errorResponse("Invalid token intepretation",401);
            }
        }
        catch(Exception $ex)
        {
            return $this->errorResponse("Unauthorised access",401);
        }

        return $next($request);
    }
}
