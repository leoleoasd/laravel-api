<?php

namespace Leoleoasd\LaravelApi;

use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class APIMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->path();
        $path = explode('/', $path);
        if ($path[0] != 'api') {
            return $next($request);
        }
        if (isset($path[1]) and preg_match('/v[0-9]*/', $path[1])) {
            if (config('api.strict_mode')) {
                throw new BadRequestHttpException('You are not allowed to define api version in URL.');
            }

            return $next($request);
        }
        $version = Tools::getVersion($request);
        if (!$version) {
            throw new BadRequestHttpException('Invalid Header.');
        }
        $newPath = '/api/'.$version;
        foreach ($path as $k => $p) {
            if ($k == 0) {
                continue;
            }
            $newPath .= '/'.$p;
        }
        $ref = new \ReflectionObject($request);
        $request->version = $version;
        $pathInfo = $ref->getProperty('pathInfo');
        $pathInfo->setAccessible(true);
        $pathInfo->setValue($request, $newPath);
        $requestURI = $ref->getProperty('requestUri');
        $requestURI->setAccessible(true);
        $requestURI->setValue($request, $newPath);

        return $next($request);
    }
}
