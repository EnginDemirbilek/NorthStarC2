<?php

function encrypt($command, $key){

    $newText = '';
    for($i=0; $i<strlen($command); )
    {
        for($j=0; ($j<strlen($key) && $i<strlen($command)); $j++,$i++)
        {
            $newText .= $command{$i} ^ $key{$j};
        }
    }

    return base64_encode($newText);
}


function decrypt($response, $key){

    $text = str_replace(' ', '+', $response);
    $text = base64_decode($text);

    $newText = '';
    for($i=0; $i<strlen($text); )
    {
        for($j=0; ($j<strlen($key) && $i<strlen($text)); $j++,$i++)
        {
            $newText .= $text{$i} ^ $key{$j};
        }
    }
    return $newText;
}

?>
