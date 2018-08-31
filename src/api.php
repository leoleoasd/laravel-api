<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Api Version
    |--------------------------------------------------------------------------
    |
    | default api version when version isn't defined in header.
    |
    */
    'version' => env("API_VERSION",'v1'),
    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | Allow define api version in url or not
    | If this is false, then request like /api/v1/foo/bar will throw an NotFoundHttpException
    |
    */
    'strict_mode' => env("API_STRICT_MODE",true),
    /*
    |--------------------------------------------------------------------------
    | Api Subtype
    |--------------------------------------------------------------------------
    |
    | A short name of your application
    |
    */
    'subtype' => env("API_SUBTYPE",'myapp'),
    /*
    |--------------------------------------------------------------------------
    | API Standards Tree
    |--------------------------------------------------------------------------
    |
    | one of 'x' 'prs' 'vnd'
    |
    */
    'standard_tree' => env("API_STANDARDS_TREE",'x')
];