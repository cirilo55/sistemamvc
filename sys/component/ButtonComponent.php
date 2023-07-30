<?php

namespace Sys\Component;

class ButtonComponent
{
    public static function render($text, $class = '', $attributes = []) {
        $attributeString = '';
        foreach ($attributes as $name => $value) {
            $attributeString .= "$name=\"$value\" ";
        }
        return "<button class=\"$class\" $attributeString>$text</button>";
    }

}