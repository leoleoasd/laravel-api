<?php

/*
 * This file is a part of leoleoasd/laravel-api.
 * Copyright (C) 2019 leoleoasd
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
        $r = ResponseJar::make($exception->data ?? [], $exception->errorCode ?? -1, get_class($exception).' '.$exception->getMessage(), $exception->statusCode ?? 500,
                [
                    'request' => $request->all(),
                    'trace' => json_decode(json_encode($exception->getTrace())) 
                ]
            );

        return $r->makeResponse();
    }
}
