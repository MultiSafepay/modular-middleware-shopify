<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Middleware;


use Closure;
use Illuminate\Http\Request;


class IpRestricted
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->getClientIp();

        abort_unless(in_array($ip, config('admin.range'), true), 403);

        $username = $request->header('PHP_AUTH_USER', null);
        $password = $request->header('PHP_AUTH_PW', null);

        if ($username && $password && $username === config('admin.user') && app('hash')->check($password, config('admin.password'))) {
            return $next($request);
        }

        $headers = array('WWW-Authenticate' => 'Basic');
        return response('Unauthorized', 401, $headers);
    }
}
