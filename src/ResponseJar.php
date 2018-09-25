<?php

/*
 * This file is part of the leoleoasd/laravel-api.
 *
 * (c) Leo Lu <luyuxuanleo@gmail.com>
 *
 * This source file is subject to the GPLV3 license that is bundled.
 */

namespace Leoleoasd\LaravelApi;

use Illuminate\Http\Response;
use JMS\Serializer\SerializerBuilder;

class ResponseJar
{
    /**
     *  The response data.
     *
     * @var
     */
    public $data;
    /**
     * API result code or error code.
     *
     * @var
     */
    public $code;
    /**
     *  Error message.
     *
     * @var
     */
    public $errmsg;
    /**
     * Http status code.
     *
     * @var
     */
    public $status_code;
    /**
     * Debug messages.
     *
     * @var
     */
    public $debug;

    /**
     * Make a ResponseJar.
     *
     * @param $data
     * @param int    $code
     * @param string $errmsg
     * @param int    $status_code
     * @param array  $debug
     *
     * @return ResponseJar
     */
    public static function make($data, $code = 0, $errmsg = '', $status_code = 200, $debug = [])
    {
        $res = new self();
        $res->data = $data;
        $res->code = $code;
        $res->errmsg = $errmsg;
        $res->status_code = $status_code;
        if ($debug == []) {
            $debug = Tools::$request->toArray();
        }
        $res->debug = $debug;

        return $res;
    }

    /**
     * Make a Laravel Response.
     *
     * @return Response
     */
    public function makeResponse()
    {
        if (!config('app.debug')) {
            $this->debug = [];
        }
        $serializer = SerializerBuilder::create()->build();
        $content = $serializer->serialize($this, Tools::$header['formatter']);
        $resp = new Response($content);
        $resp->header('Content-Type', 'application/'.Tools::$header['formatter']);
        $resp->setStatusCode($this->status_code ?? 500);
        return $resp;
    }
}
