<?php

/*
Encrypt or decrypt given text with specified xor key.
*/
function endecrypt($response, $key){

    $text = str_replace(' ', '+', $response);
    $netText = '';
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
