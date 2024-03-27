<?php

namespace Sys\Component;

class InputLabelComponent{
    public static function render($label, $type, $name, $value='', $attributes  = [])
    {
        $attributeString = '';

        foreach($attributes as $attr => $val)
        {
            $attributeString .= "$attr=\"$val\" ";

        }
        return "<div class='container'>
                    <div class='input-label-component'>
                        <div>
                            <label for=\"$label\" >$label</label>
                        </div>
                            <input type=\"$type\" name=\"$name\" value=\"$value\" $attributeString />
                    </div>
                </div>
                ";
    }
}
?>
<style scoped>
.container{
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    margin-bottom: 10px;

}
.input-label-component{
    height: 42px;
    width: 80%;

    border: 1px solid black; 
    border-radius: 4px;
    font-size:18px;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
    background-color: #DCDCDC;
    transition: border-color 0.3s, color 0.3s;
    padding: 5px;

    &:focus-within{
        border-color: blue;
        label{
            color: blue
            
        }
    }
    
}
.input-label-component input{
    border: 0 !important;
    text-shadow:1px 0px 0px rgba(42,42,42,.49); 
    font-family: sans-serif; 
    border-color:#fafafa;
    font-size: 16px;
    width: 100%;
    padding: 0;
    background-color: #DCDCDC;

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
.input-label-component input:focus {
    outline: none; 
}

 
</style>