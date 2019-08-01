<?php

namespace Permafrost\ExpandedDates;

/**
 * Class ExpandedDate
 * @package Permafrost\ExpandedDates
 *
 * @property-read string $value
 * @property-read string $relative
 * @property-read string $timezone
 * @property-read string $formatted
 * @property-read string $time
 * @property-read string $age
 */
class ExpandedDate extends \stdClass
{
    public static function assign(array $props)
    {
        $result = new static;

        foreach($props as $key => $value) {
            $result->$key = $value;
        }

        return $result;
    }
}