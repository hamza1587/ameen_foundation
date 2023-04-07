<?php
header("Content-Type: text/html");
function API($to, $text)
{
    $url1 = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp';
    $fields1 = array(
        'msisdn' => '923417589011',
        'password' => 'Khurrianwala@135790'
    );

    //url-ify the data for the POST
    $counter1 = 0;
    $fields_string1 = "";
    foreach ($fields1 as $key => $value) {
        if ($counter1 > 0) {
            $fields_string1 .= '&';
        }
        $fields_string1 .= $key . '=' . $value;
        $counter1++;
    }
    rtrim($fields_string1, '&');

    //open connection
    $ch1 = curl_init($url1);

    //set the url, number of POST vars, POST data
    curl_setopt($ch1, CURLOPT_URL, $url1);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $fields_string1);
    curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch1, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
    $result1 = curl_exec($ch1);
    $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
    $array_data = json_decode(json_encode(simplexml_load_string($result1)), true);
    $session_id = $array_data['data'];
    if (curl_exec($ch1) === false) {
        echo 'Curl error: ' . curl_error($ch1);
    } else {
        //set POST variables
        $url = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp';
        $fields = array(
            'session_id' => $session_id,
            'to' => "92".$to,
            'text' => $text,
            'mask' => 'AMEEN FOUND'
        );

        //url-ify the data for the POST
        $counter = 0;
        $fields_string = "";
        foreach ($fields as $key => $value) {
            if ($counter > 0) {
                $fields_string .= '&';
            }
            $fields_string .= $key . '=' . $value;
            $counter++;
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init($url);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL


        //execute post
        $result = curl_exec($ch);

        if (curl_exec($ch) === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            echo 'Operation completed without any errors';
        }

        //close connection
        curl_close($ch);
    }
}
