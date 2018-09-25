<?php

/*
 * This file is part of the leoleoasd/laravel-api.
 *
 * (c) Leo Lu <luyuxuanleo@gmail.com>
 *
 * This source file is subject to the GPLV3 license that is bundled.
 */

namespace Leoleoasd\LaravelApi;

use App\Exceptions\Handler as ExceptionHandler;
use Exception;

class ErrorHandler extends ExceptionHandler
{
    public function render($request, Exception $exception)
    {
        if (!$request->isAPI) {
            return parent::render($request, $exception);
        }
        $r = ResponseJar::make($exception->data ?? [], $exception->errorCode ?? -1, get_class($exception).$exception->getMessage(), $exception->statusCode ?? 500,
                [
                    'request' => $request->all(),
                    'trace' => $exception->getTrace(),
                ]
            );

        return $r->makeResponse();
    }
}