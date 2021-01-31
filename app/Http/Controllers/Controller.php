<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getHistoricalData(Request $request) {
        $validatedData  = $this->validate($request, [
            'from' => 'required|date|date_format:Y-m-d',
            'to' => 'required|date|after:from|date_format:Y-m-d|before_or_equal:now',
        ]);
        $from = $validatedData['from'];
        $to = $validatedData['to'];

        /**
         * Reading Data and transforming into the format
         * {DATE:PRICE}
         * 
         * To avoid propogation of changes from coindesk api in the future 
         */

        $json = file_get_contents("https://api.coindesk.com/v1/bpi/historical/close.json?start=$from&?end=$to");
        $data = json_decode($json);
        return json_encode($data->bpi);
    }
}
