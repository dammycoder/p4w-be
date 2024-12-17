<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
 public function handle($request, Closure $next)
 {
     $allowedOrigins = [
         'https://p4-w-fe.vercel.app',
         'http://localhost:3000',
         'https://partnershipforwellbeing.org',
     ];
 
     $origin = $request->headers->get('Origin');
     
     if (in_array($origin, $allowedOrigins)) {
         return $next($request)
             ->header('Access-Control-Allow-Origin', $origin) 
             ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
             ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, Accept')
             ->header('Access-Control-Allow-Credentials', 'true');
     }
 
     return $next($request);
 }
 
}