<?php
namespace App\Http\Middleware;
use Closure;

class CheckBanned
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()&&auth()->user()->isBanned()) {
            
            auth()->logout();

            
            $message = 'Your account has been suspended. Please contact administrator.';


            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}