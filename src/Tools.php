<?php

/*
 * This file is part of the leoleoasd/laravel-api.
 *
 * (c) Leo Lu <luyuxuanleo@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Leoleoasd\LaravelApi;

use Illuminate\Http\Request;

class Tools
{
    /**
     * @var Request
     */
    public static $request;

    /**
     * @var array
     */
    public static $header;

    /**
     * Analyse an incoming header.
     *
     * @params string $accept
     *
     * @return array
     */
    public static function analyseHeader($accept)
    {
        $preg = '/application\/(x|vnd|prs).([A-Za-z0-9]*).(v[0-9]*)(\+(json|xml))?/';
        preg_match($preg, $accept, $match);
        if ($match == []) {
            return [];
        }
        if ('' == $match[5]) {
            $match[5] = 'json';
        }

        return [
            'standard_tree' => $match[1],
            'subtype' => $match[2],
            'version' => $match[3],
            'formatter' => $match[5],
        ];
    }

    /**
     * Get api version defined in header.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public static function getVersion()
    {
        $header = self::$header;
        if ($header) {
            if ($header['standard_tree'] == config('api.standard_tree') and
                $header['subtype'] == config('api.subtype')) {
                return $header['version'];
            }
            if (config('api.strict_mode')) {
                return null;
            }

            return config('api.default_version');
        }
    }

    public static function init(Request $request)
    {
        self::$request = $request;
        self::$header = self::analyseHeader($request->header('Accept'));
    }

    public function __call($method, $args)
    {
        return static::$method(...$args);
    }
}
