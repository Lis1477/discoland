<?php

namespace App\Traits;

trait CdekApiTrait
{
    public function getToken()
    {
        // Получаем токен
        $array = array();
        $array['grant_type']    = 'client_credentials';
        $array['client_id']     = 'YMSunzKwf6iVF1gfR88JcT52xJexKRTR'; 
        $array['client_secret'] = '7YCeYxq92n3H7kQtFXpfNqJTDOJc072C'; 
         
        $ch = curl_init('https://api.cdek.ru/v2/oauth/token?parameters');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&')); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($html, true);
         
        $access_token = $res['access_token'];

        return $access_token;
    }

    public function getCity($data)
    {

        // формируем строку запроса
        $query_str = 'city='.$data['search_string'];

        if ($data['country_code']) {
            $query_str .= '&country_codes='.$data['country_code'];
        }


     
        $ch = curl_init('https://api.cdek.ru/v2/location/cities?'.$query_str);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&')); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$data['access_token']
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($html);

        return $res;
    }

    public function getDeliveryPrice($data)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.cdek.ru/v2/calculator/tarifflist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "type": 1,
                "from_location": {
                    "code": 9220
                },
                "to_location": {
                    "code": '.$data['city_code'].'
                },
                "packages": [
                    {
                        "weight": 600,
                        "height": 35,
                        "width": 35,
                        "length": 2
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$data['access_token']
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    public function getPV($data)
    {

        // формируем строку запроса
        $query_str = 'city_code='.$data['city_code'].'&type=PVZ';
     
        $ch = curl_init('https://api.cdek.ru/v2/deliverypoints?'.$query_str);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&')); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$data['access_token']
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($html);

        return $res;
    }
}