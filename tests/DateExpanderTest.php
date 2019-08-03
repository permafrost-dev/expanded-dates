<?php

declare(strict_types=1);

namespace Permafrost\ExpandedDates\Tests;

use Carbon\Carbon;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use Permafrost\ExpandedDates\DateExpander;
use Permafrost\ExpandedDates\ExpandedDate;

/**
 * @uses \Permafrost\ExpandedDates\DateExpander
 *
 * @covers \Permafrost\ExpandedDates\DateExpander
 */
class DateExpanderTest extends TestCase
{
    /**
     * @test
     * @testdox filters an array using its keys
     */
    public function it_can_filter_an_array_using_its_keys() : void
    {
        $expander = new DateExpander();
        $items = ['one' => 1, 'two' => 2, 'three' => 3];
        $keys = ['one','three'];

        $result = $expander->filterTypesByKey($items, $keys);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(array_keys($result), $keys);
        $this->assertEquals($result, ['one' => 1, 'three' => 3]);
    }

    /**
     * @test
     * @testdox calculates age properties correctly
     */
    public function it_calculates_age_properties_correctly() : void
    {
        $expander = new DateExpander();
        $age = $expander->getFullExpandedAgeArray(Carbon::now()->subHours(1)->subMinutes(2)->subSeconds(35));

        $this->assertIsArray($age);

        $actualKeys = array_keys($age);

        $expectedKeys = DateExpander::EXPANDED_AGE_TYPES;
        $this->assertIsArray($expectedKeys);

        sort($actualKeys);
        sort($expectedKeys);

        $this->assertEquals($expectedKeys, $actualKeys);

        $this->assertEquals($age['seconds'], 3600 + 120 + 35);
        $this->assertEquals($age['minutes'], 60 + 2);
        $this->assertEquals($age['hours'], 1);
        $this->assertEquals($age['days'], 0);
        $this->assertEquals($age['weeks'], 0);
    }

    /**
     * @test
     * @testdox calculates date properties correctly
     */
    public function it_calculates_date_properties_correctly() : void
    {
        $expander = $this->createTestProxy(DateExpander::class, []);
        $now = Carbon::now();

        $date = $expander->getFullExpandedDateArray($now);

        $this->assertIsArray($date);

        $actualKeys = array_keys($date);

        $expectedKeys = DateExpander::EXPANDED_DATE_TYPES;
        $this->assertIsArray($expectedKeys);

        sort($actualKeys);
        sort($expectedKeys);

        $this->assertEquals($expectedKeys, $actualKeys);
        $this->assertEquals($date['value'], $now->toIso8601String());
        $this->assertEquals($date['relative'], $now->diffForHumans());
        $this->assertEquals($date['formatted'], $now->toFormattedDateString());
        $this->assertEquals($date['time'], $now->format($expander->timeFormat));
        $this->assertEquals($date['timestamp'], $now->timestamp);
        $this->assertEquals($date['day'], $now->dayName);
    }

    /**
     * @test
     * @testdox expands a date string correctly and returns an ExpandedDate object
     */
    public function it_expands_a_date_string_correctly() : void
    {
        $expander = new DateExpander();
        $now = Carbon::now();

        $date = $expander->expand(date('Y-m-d H:i:s'));

        $this->assertEquals(get_class($date), ExpandedDate::class);
        $this->assertEquals($date->value, $now->toIso8601String());
        $this->assertEquals($date->relative, $now->diffForHumans());
        $this->assertEquals($date->formatted, $now->toFormattedDateString());
        $this->assertEquals($date->time, $now->format($expander->timeFormat));
        $this->assertEquals($date->timestamp, $now->timestamp);
        $this->assertEquals($date->day, $now->dayName);
    }
}
