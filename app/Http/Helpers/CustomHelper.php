<?php

namespace App\Http\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomHelper
{
    /**
     * Format the validation response.
     *
     * @param  Illuminate\Support\Facades\Validator message[]
     * @return string[] An array of the formatted validation response as per need.
     */
    public static function prepareValidationResponse($validation = array())
    {

        $validation_data = json_encode($validation);
        $validation_data = json_decode($validation_data);

        $errors = array();
        foreach ($validation_data as $key => $value) {
            $errors[] = array(
                "field" => $key,
                "message" => $value[0]
            );
        }

        return $response = array(
            "status" => false,
            "message" => 'Validation error',
            "data" => [],
            "errors" => $errors
        );
    }

}
