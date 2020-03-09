<?php
namespace App\Http\Middleware;

use Closure;
use Config;

class AuthenticateWithToken
{

	public function handle($request, Closure $next)
	{
		$tokens = Config::get("auth.tokens");
		$header = $request->header("Authorization");

		$check_token = explode(" ", $header);

		if(!isset($check_token[1]) || !in_array($check_token[1], $tokens)){
			return response()->json(["message"=>"Unauthorized"], 401);
		}
		return $next($request);
	}
}

?>