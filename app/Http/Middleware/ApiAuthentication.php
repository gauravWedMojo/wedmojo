<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\User;
use Response;
class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(!empty(($request->header('accessToken')))){
            $userDetail = User::where(['remember_token' => $request->header('accessToken')])->first();
            if(empty($userDetail)){
                $Response = [
                  'message'  => trans('messages.invalid.detail'),
                ];
                return Response::json( $Response , trans('messages.statusCode.INVALID_ACCESS_TOKEN') );
            }
            $request['userDetail'] = $userDetail;
            return $next($request);
        } else {
            $response['message'] = __('messages.required.accessToken');
            return response()->json($response,401);
        }
    }
}
