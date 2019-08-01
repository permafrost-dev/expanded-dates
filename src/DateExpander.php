<?php

namespace Permafrost\ExpandedDates;

use Carbon\Carbon;

class DateExpander
{
    /** @var array */
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
     * Generates an object representing an 'age' as seconds, minutes, hours, days, and weeks.
     *
     * Result property values are positive when:
     *      $now > $date ($date->isPast())
     *
     * Result property values are negative when:
     *      $now < $date ($date->isFuture())
     *
     * @param \Carbon\Carbon|string $date
     * @return object
     */
    protected function createExpandedAgeObject($date, array $types = self::EXPANDED_AGE_TYPES)
    {
        $now = Carbon::now();
        $ageSeconds = $now->diffInSeconds($date, false) * (- 1);

        $result = [
            'seconds' => $ageSeconds,
            'minutes' => (int)floor($ageSeconds / 60),
            'hours' => (int)floor((int)floor($ageSeconds / 60) / 60),
            'days' => $now->diffInDays($date) * (- 1),
            'weeks' => $now->diffInWeeks($date) * (- 1),
        ];

        return (object)array_filter($result, function ($key) use ($types) {
            return in_array($key, $types, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function createExpandedDateObject(Carbon $date, array $types = self::EXPANDED_DATE_TYPES)
    {
        $result = [
            'value' =>  $date->toIso8601String(),
            'relative' => $date->diffForHumans(),
            'formatted' => $date->toFormattedDateString(),
            'time' => $date->format($this->timeFormat),
            'timestamp' => $date->timestamp,
            'day' => $date->dayName,
            'age' => $this->createExpandedAgeObject($date, ['hours','seconds']),
        ];

        return ExpandedDate::assign(
            array_filter($result, function ($key) use ($types) {
                    return in_array($key, $types, true);
                }, ARRAY_FILTER_USE_KEY
            )
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
     * @param Model $model
     * @param \Carbon\Carbon|string $date
     * @param string $conditional
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

/*
include __DIR__.'/../vendor/autoload.php';
$de = new DateExpander();
$ed1 = $de->expand(Carbon::now()->subSeconds(35));
print_r($ed1);
*/