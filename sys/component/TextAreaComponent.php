<?php

namespace Sys\Component;

class TextAreaComponent{
    public static function render($name, $type, $value, $attributes  = [])
    {
        $attributeString = '';

        foreach($attributes as $attr => $val)
        {
            $attributeString .= "$attr=\"$val\" ";

        }
        return "<textarea type=\"$type\" name=\"$name\" value=\"$value\" $attributeString> </textarea>";
    }
}