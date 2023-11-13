<?php

namespace Sys\Component;

class InputLabelComponent{
    public static function render($label, $type, $value, $attributes  = [])
    {
        $attributeString = '';

        foreach($attributes as $attr => $val)
        {
            $attributeString .= "$attr=\"$val\" ";

        }
        return "<div class='input-label-component'>
                <div>
                <label for=\"$label\" >$label</label>
                </div>
                <input type=\"$type\" name=\"$label\" value=\"$value\" $attributeString />
                </div>
                ";
    }
}
?>
<style>
.input-label-component{
    border: 1px solid black; 
    border-radius: 1px;
    font-size:18px;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
}
.input-label-component input{
    border: 0 !important;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
    font-family:sans-serif; border-color:#fafafa;
    font-size: 14px
}
.input-label-component label
{
    font-size: 10px;
    font-family:sans-serif; 
}
.submit{
    display: flex;
    flex-direction: row-reverse;
    
}
.input-label-component input:focus { outline:none; } 
</style>