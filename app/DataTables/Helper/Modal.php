<?php

namespace App\DataTables\Helper;

use Illuminate\Support\Str;

class Modal
{
    protected static $attributes = [];

    public static function make($id, $title, $dialogClass = '')
    {
        self::$attributes['title'] = Str::title($title);
        self::$attributes['id'] = $id;
        self::$attributes['dialogClass'] = $dialogClass;

        return new self;
    }

    public function setForm($formId, $action = '')
    {
        self::$attributes['formId'] = $formId;
        self::$attributes['action'] = $action;

        return  $this;
    }

    public function setContent($opt = [])
    {
        self::$attributes['content'] = $opt;

        return  $this;
    }

    public function setBtn($btn = [])
    {
        self::$attributes['button'] = $btn;
        
        return  $this;
    }

    public function render()
    {
        return self::$attributes;
    }
}
