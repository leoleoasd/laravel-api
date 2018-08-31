<?php
namespace Leoleoasd\LaravelApi;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Tools{

    /**
     * Analyse the incoming header.
     *
     * @params string $accept
     * @return array
     */
    public static function analyseHeader($accept){
        $preg = '/application\/(x|vnd|prs).([A-Za-z0-9]*).(v[0-9]*)/';
        preg_match($preg,$accept,$match);
        if($match == []){
            return null;
        }
        return [
            'standard_tree' => $match[1],
            'subtype' => $match[2],
            'version' => $match[3]
        ];
    }

    /**
     * Get api version defined in header
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function getVersion($request){
        $header = self::analyseHeader($request->header("Accept"));
        if($header){
            if($header['standard_tree'] == config('api.standard_tree') and
                $header['subtype'] == config("api.subtype")){
                return $header['version'];
            }
            if(config('api.strict_mode')){
                return null;
            }
            return config('api.default_version');
        }
        return null;
    }

    public function __call($method,$args){
        return static::$method(...$args);
    }
}