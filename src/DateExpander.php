<?php

namespace Permafrost\ExpandedDates;

use Carbon\Carbon;

class DateExpander
{
    public const EXPANDED_DATE_TYPES = [
        'age',
        'day',
        'formatted',
        'relative',
        'time',
        'timestamp',
        'value',
    ];

    public const EXPANDED_AGE_TYPES = [
        'seconds',
        'minutes',
        'hours',
        'days',
        'weeks',
    ];

    public $timeFormat = 'g:i A';


    /**
     * Filters array $items and returns only the key/value pairs that exist in $types (array of key names)
     *
     * @param $items
     * @param $types
     *
     * @return object
     */
    public function filterTypesByKey($items, $types)
    {
        return array_filter($items, static function ($key) use ($types) {
            return in_array($key, $types, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Creates complete expanded age object data, returned as an array
     *
     * @param \Carbon\Carbon $date
     *
     * @return array
     */
    public function getFullExpandedAgeArray(Carbon $date)
    {
        $now = Carbon::now();
        $ageSeconds = $now->diffInSeconds($date, false) * (-1);

        return [
            'seconds'=>$ageSeconds,
            'minutes'=>(int)floor($ageSeconds / 60),
            'hours'=>(int)floor((int)floor($ageSeconds / 60) / 60),
            'days'=>$now->diffInDays($date) * (-1),
            'weeks'=>$now->diffInWeeks($date) * (-1),
        ];
    }

    /**
     * Creates complete expanded date object data, returned as an array
     *
     * @param \Carbon\Carbon $date
     *
     * @return array
     */
    public function getFullExpandedDateArray(Carbon $date)
    {
        return [
            'value'=>$date->toIso8601String(),
            'relative'=>$date->diffForHumans(),
            'formatted'=>$date->toFormattedDateString(),
            'time'=>$date->format($this->timeFormat),
            'timestamp'=>$date->timestamp,
            'day'=>$date->dayName,
            'age'=>$this->createExpandedAgeObject($date),
        ];
    }

    public function getFilteredExpandedDateArray($date, $types = self::EXPANDED_DATE_TYPES)
    {
        return $this->filterTypesByKey(
            $this->getFullExpandedDateArray($date),
            $types
        );
    }

    /**
     * Create an expanded date object
     *
     * @param \Carbon\Carbon $date
     * @param array $types
     *
     * @return object
     */
    protected function createExpandedDateObject(Carbon $date, array $types = self::EXPANDED_DATE_TYPES)
    {
        return ExpandedDate::assign(
            $this->getFilteredExpandedDateArray($date, $types)
        );
    }

    /**
     * Generates an object representing an 'age' as seconds, minutes, hours, days, and weeks.
     *
     * Result property values are positive when:
     *      $now > $date ($date->isPast())
     *
     * Result property values are negative when:
     *      $now < $date ($date->isFuture())
     *
     * @param \Carbon\Carbon|string $date
     *
     * @return object
     */
    protected function createExpandedAgeObject($date, array $types = self::EXPANDED_AGE_TYPES)
    {
        return (object)$this->filterTypesByKey(
            $this->getFullExpandedAgeArray($date),
            $types
        );
    }

    /**
     * Converts a $date (carbon instance) into several common date formats.
     * $properties is a list of which formats to return, or `['*']` to
     * return all formats.
     * if $date is a string, first it is considered to be a property of
     * the class, and if not, then is assumed to be a date string and
     * attempts to parse it and convert to Carbon.
     *
     * You can pass a Eloquent Model's created_at/updated_at properties directly
     * in as the $date parameter.
     *
     * @param Model                 $model
     * @param \Carbon\Carbon|string $date
     * @param string                $conditional
     *
     * @return ExpandedDate
     */
    public function expand($date): object
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $this->createExpandedDateObject($date);
    }
}