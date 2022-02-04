<?php

namespace PhpHelpers;

/** For outputting data to a browser window in a readable format */
function DebugPrint($array, string $label = "")
{
    $labelStr = "";
    if ($label !== "") {
        $labelStr = "<br/>$label : ";
    }
    print("$labelStr <pre>" . print_r($array, true) . "</pre><br/>");
}
