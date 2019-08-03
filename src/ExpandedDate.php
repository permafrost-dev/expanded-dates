<?php

namespace Permafrost\ExpandedDates;

/**
 * Class ExpandedDate.
 *
 * @property string $value
 * @property string $relative
 * @property string $timezone
 * @property string $formatted
 * @property string $time
 * @property string $age
 */
class ExpandedDate extends \stdClass
{
    /**
     * Assigns all key/value pairs in $props as properties on the returned object.
     *
     * @param array $props
     *
     * @return \Permafrost\ExpandedDates\ExpandedDate
     */
    public static function assign(array $props)
    {
        $result = new static();

        foreach ($props as $key=>$value) {
            $result->$key = $value;
        }

        return $result;
    }

    public static function create($date)
    {
        $expander = new DateExpander();
        return static::assign(
            $expander->getFilteredExpandedDateArray($date)
        );
    }
}
