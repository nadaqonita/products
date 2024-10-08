<?php

namespace App\DataTables\Helper;

use Illuminate\Support\Str;

class Column
{
    protected static $attributes = [];
    
    public static function make($data, $option = [])
    {
        self::$attributes['data'] = $data;
        self::$attributes['name'] = isset($option['name']) ? $option['name'] : $data;
        self::$attributes['title'] = isset($option['title']) ? $option['title'] : Str::title(Str::replace('_', ' ', $data));
        self::$attributes['orderable'] = isset($option['orderable']) ? $option['orderable'] : true;
        self::$attributes['searchable'] = isset($option['searchable']) ? $option['searchable'] : true;
        self::$attributes['visibility'] = isset($option['visibility']) ? $option['visibility'] : true;
        self::$attributes['type'] = isset($option['type']) ? $option['type'] : 'text';
        self::$attributes['option'] = isset($option['option']) ? $option['option'] : [];
        self::$attributes['render'] = isset($option['render']) ? $option['render'] : 'function(){}';
        self::$attributes['width'] = isset($option['width']) ? $option['width'] : '';
        self::$attributes['className'] = isset($option['className']) ? $option['className'] : '';
        self::$attributes['exportable'] = isset($option['exportable']) ? $option['exportable'] : true;
        self::$attributes['content'] = isset($option['content']) ? $option['content'] : '';
        self::$attributes['columnType'] = isset($option['columnType']) ? $option['columnType'] : 'plain';

        return self::$attributes;
    }
}
