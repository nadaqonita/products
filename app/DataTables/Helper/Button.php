<?php

namespace App\DataTables\Helper;

class Button
{
    public static function make($text, $option = ["extend" => '', "position" => 'left', "type" => "button", 'action' => '', 'attr' => []])
    {
        $attributes = [];
        $attributes['text'] = $text;

        if (isset($option['action'])) {
            $attributes['action'] = $option['action'] ?? '';
        }

        $attributes['extend'] = isset($option['extend']) ? $option['extend'] : '';
        $attributes['position'] = isset($option['position']) ? $option['position'] : 'left';
        $attributes['type'] = isset($option['type']) ? $option['type'] : 'button';
        $attributes['attr'] = isset($option['attr']) ? $option['attr'] : '';

        if (isset($option['exportOptions'])) {
            $attributes['exportOptions'] = $option['exportOptions'];
        }

        return $attributes;
    }
}
