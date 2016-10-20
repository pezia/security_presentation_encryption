<?php

function echoChunked($text, $size)
{
    $chunks = str_split($text, $size);

    echo implode(' - ', $chunks);
}
