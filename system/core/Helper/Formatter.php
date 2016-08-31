<?php

namespace PmsOne\Helper;


class Formatter
{
    public static function formatAttributes(array $attributes)
    {
        $return = '';

        foreach ($attributes as $key => $val) {

            if (is_int($key)) {
                $return .= ' ' . $val;
            } else {
                if (is_array($val)) {
                    $val = implode(' ', $val);
                }

                $valString = '';
                if ($val != '') {
                    $valString = '="' . htmlspecialchars($val) . '"';
                }

                $return .= ' ' . htmlspecialchars($key) . $valString;
            }
        }

        return $return;
    }
}