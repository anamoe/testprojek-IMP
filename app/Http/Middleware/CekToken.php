<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CekToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
    

            // get token from request header
            $token = $request->header('Authorization');

            // check role and token

                // get token with role 'pengirim'
                $user = User::where('api_token', $token)->first();

                if ($user) {
                    // if token match with database result,
                    // bypass the request to the next process
                    return $next($request);
                } else {
                    // if token mismatch,
                    // return the error message
                    return response()->json([
                        'status'    => 'failed',
                        'message'   => 'token invalid, unauthorized!',
                        'data'      => []
                    ], 401);
                }

            
        } catch (\Throwable $th) {
            // catch error, return the error message
            return response()->json([
                'status'    => 'error',
                'message'   => $th->getMessage(),
                'data'      => []
            ], 500);
        }
    }
}
