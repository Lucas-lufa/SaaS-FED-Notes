<?php

namespace App\Controllers;

class ErrorController
{
    // 404 error not found
    // return void

    public static function notFound(){
        http_response_code(404);

        loadView('error',[
            'error'=> '404',
            'message'=> 'resource not found.'
        ]);
    }

    public static function unauthorised($message = 'you need to be logged in to access this resource.'){
        http_response_code(403);

        loadView('error',[
            'error'=> '403',
            'message'=> $message
        ]);
    }
}