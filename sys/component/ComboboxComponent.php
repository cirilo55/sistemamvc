<?php

namespace Sys\Component;


class ComboboxComponent {
    public static function render($name, $options = [], $selected = '', $attributes = []) {
        $attributeString = '';
        foreach ($attributes as $attr => $val) {
            $attributeString .= "$attr=\"$val\" ";
        }
        
        $optionsHtml = '';
        foreach ($options as $value => $label) {
            $selectedAttr = $value == $selected ? 'selected' : '';
            $optionsHtml .= "<option value=\"$value\" $selectedAttr>$label</option>";
        }
        
        return "<select name=\"$name\" $attributeString>$optionsHtml</select>";
    }
}