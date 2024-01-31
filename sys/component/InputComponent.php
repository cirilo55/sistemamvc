<?php
namespace Sys\Component;

class InputComponent {
    public static function render($name, $label, $type, $value, $attributes = [])
    {
        $attributeString = '';

        foreach ($attributes as $attr => $val) {
            $attributeString .= "$attr=\"$val\" ";
        }
        echo "<div class='input-label-component'>";
        echo "<div class='hidden-$name' style='display:none'></div>";
        echo "<div class='flex'>";
        echo "<label>$label</label><div class='input-component-count'style='display:none'><div class='current-input'>0</div><div class='max-inputs'>/27</div></div>";
        echo "</div>";
        echo "<input class='input-component' id='input-$name' type='$type' name='$name' value='$value' $attributeString />";
        echo "</div>";
    }
}
?>
<style>
    .input-label-component {
        border: 1px solid black;
        border-radius: 1px;
        font-size: 18px;
        text-shadow: 1px 0px 0px rgba(42, 42, 42, .49);
        padding: 3px;
    }

    .input-label-component input {
        border: 0 !important;
        text-shadow: 1px 0px 0px rgba(42, 42, 42, .49);
        font-family: sans-serif;
        border-color: #fafafa;
        font-size: 14px;
        width: 100%;
    }
    .input-component-count{
        display: flex;
        margin-left: auto;
        font-size: 10px;
        color:gray;
        width: 5%;
    }   


    .input-label-component input:hover {
        border: 0 !important;
    }

    .input-label-component label {
        font-size: 10px;
        font-family: sans-serif;
    }
</style>
<script>
    $(".input-component").on('focus', function(){
        $('.input-component-count').show();
    })

</script>
