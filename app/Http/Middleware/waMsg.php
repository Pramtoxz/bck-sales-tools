<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Digital\TokenDigitalMa;
class waMsg
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // static token di database
        $getToken = TokenDigitalMa::where('module','wa_msg')->first();
        $staticToken = $getToken->token ?? null;
        $getToken = $request->header("Authorization");
        if($getToken == ""){
            return response()->json([
                "code"=>403,
                "status"=>false,
                "data"=>null,
                "message"=>"Request Tidak Valid"
            ]);
        }else{
            $implodeToken = explode(" ",$getToken);
            if($implodeToken[1] == $staticToken){
                return $next($request);
            }else{
                return response()->json([
                    "code"=>403,
                    "status"=>false,
                    "data"=>null,
                    "message"=>"Token Tidak Valid"
                ]); 
            }
        }
    }
}
