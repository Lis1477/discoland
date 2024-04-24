<?php

namespace App\Http\Controllers;

use App\Traits\CdekApiTrait;


// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Http;
// use CdekSDK\Requests;

// use GuzzleHttp\Client;

class SdekController extends Controller
{
    use CdekApiTrait;

    public function testSdek()
    {
        // $client = new \CdekSDK\CdekClient('YMSunzKwf6iVF1gfR88JcT52xJexKRTR', '7YCeYxq92n3H7kQtFXpfNqJTDOJc072C');

        $access_token = $this->getToken();

dd($access_token);


        return view('test_sdek');
    }

}
