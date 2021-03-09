
<?php

class Curl
{

    function curlFetch($location)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $location);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $curlData = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $curlData;
    }
}
