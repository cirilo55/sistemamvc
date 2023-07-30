<?php

namespace Sys\Component;

class InputComponent{
    public static function render($name, $type, $value, $attributes  = [])
    {
        $attributeString = '';

        foreach($attributes as $attr => $val)
        {
            $attributeString .= "$attr=\"$val\" ";

        }
        return "<input type=\"$type\" name=\"$name\" value=\"$value\" $attributeString />";
    }
}