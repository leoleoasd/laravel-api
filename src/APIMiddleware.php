<?php

/*
 * This file is a part of leoleoasd/laravel-api.
 * Copyright (C) 2019 leoleoasd
 */

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
        if ('api' != $path[0]) {
            return $next($request);
        }
        $request->isAPI = true;
        if (isset($path[1]) and preg_match('/v[0-9]*/', $path[1])) {
            if (config('api.strict_mode')) {
                throw new BadRequestHttpException('You are not allowed to define api version in URL.');
            }

            return $next($request);
        }
        Tools::init($request);
        $version = Tools::getVersion();
        if (!$version) {
            throw new BadRequestHttpException('Invalid Header.');
        }
        $newPath = '/api/'.$version;
        foreach ($path as $k => $p) {
            if (0 == $k) {
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
        $response = $next($request);
        if (!(isset($response->is_serialized) and $response->is_serialized)) {
            try {
                $rep = json_decode($response->getContent());
            } catch (\Exception $e) {
                $rep = null;
            }
            if ($rep) {
                $rep = ResponseJar::make($rep, 0, '');
            } else {
                $rep = ResponseJar::make($response->getContent(), 0, '');
            }

            return $rep->makeResponse();
        }

        return $response;
    }
}
