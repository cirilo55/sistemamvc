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
?>

<style>
    .btn-primary{
    background-color: purple;
    width: calc(100% - 40px);
    color: white;
    border: 2px solid purple;
    border-radius: 5px;
    margin-right: 20px ;
    height: 5vh;
    cursor: pointer;
    }
    button:hover{
    opacity: 0.6;

    }
</style>