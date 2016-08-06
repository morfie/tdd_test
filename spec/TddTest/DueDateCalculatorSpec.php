<?php

namespace spec\TddTest;

use TddTest\DueDateCalculator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TddTest\Issue;

/**
 * @mixin DueDateCalculator
 */
class DueDateCalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DueDateCalculator::class);
    }

    function it_get_eight_workhours_should_be_next_day() {
        $this
            ->calculateDueDate(new \DateTimeImmutable('2016-08-03 10:12'), 8)
            ->shouldBeLike(new \DateTimeImmutable(('2016-08-04 10:12')));
    }

    function it_should_not_allow_turnaround_negativ_value() {
        $this->shouldThrow(\InvalidArgumentException::class)->during('calculateDueDate', array(new \DateTimeImmutable, -100));
    }

    function it_should_not_allow_submit_date_out_of_working_hours() {
        $this->shouldThrow(\OutOfRangeException::class)->during('calculateDueDate', array(new \DateTimeImmutable('2016-08-04 19:00'), 10));
    }

    function it_should_not_allow_submit_date_out_of_working_days() {
        $this->shouldThrow(\OutOfRangeException::class)->during('calculateDueDate', array(new \DateTimeImmutable('2016-08-06 12:00'), 10));
    }
}
