<?php

namespace PhpHelpers;

class Util
{
    /** For outputting data to a browser window in a readable format */
    public static function DebugPrint($o, string $label = "")
    {
        $labelStr = "";
        if ($label !== "") {
            $labelStr = "<br/>$label : ";
        }
        print("$labelStr <pre>" . print_r($o, true) . "</pre><br/>");
    }
}
