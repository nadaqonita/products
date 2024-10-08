<?php

namespace App\DataTables\Helper;

use Illuminate\Support\Str;

class Form
{
    protected static $attributes = [];

    public static function make($tag, $name, $option = [])
    {
        self::$attributes['tag'] = $tag;
        self::$attributes['name'] = $name;
        self::$attributes['id'] = $name;
        self::$attributes['type'] = isset($option['type']) ? $option['type'] : 'text';
        self::$attributes['class'] = isset($option['class']) ? $option['class'] : '';
        self::$attributes['readonly'] = isset($option['readonly']) ? 'readonly' : '';
        self::$attributes['disabled'] = isset($option['disabled']) ? 'disabled' : '';
        self::$attributes['value'] = isset($option['value']) ? $option['value'] : null;
        self::$attributes['label'] = isset($option['label']) ? $option['label'] : Str::title(Str::replace('_', ' ', $name));
        self::$attributes['column'] = isset($option['column']) ? $option['column'] : 12;
        self::$attributes['option'] = isset($option['option']) ? $option['option'] : '';
        self::$attributes['placeholder'] = isset($option['placeholder']) ? $option['placeholder'] : Str::title(Str::replace('_', ' ', $name));
        self::$attributes['spanClass'] = isset($option['spanClass']) ? $option['spanClass'] : '';
        self::$attributes['span'] = isset($option['span']) ? $option['span'] : '';
        self::$attributes['spanId'] = isset($option['spanId']) ? $option['spanId'] : '';
        self::$attributes['spanValue'] = isset($option['spanValue']) ? $option['spanValue'] : '';
        self::$attributes['spanId2'] = isset($option['spanId2']) ? $option['spanId2'] : '';
        self::$attributes['spanValue2'] = isset($option['spanValue2']) ? $option['spanValue2'] : '';
        self::$attributes['divId'] = isset($option['divId']) ? $option['divId'] : '';
        self::$attributes['required'] = isset($option['required']) ? $option['required'] == true ? true : false : false;
        
        return self::$attributes;
    }
}
