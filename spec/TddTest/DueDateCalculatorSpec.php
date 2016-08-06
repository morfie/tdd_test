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
        $dueDate = $this->calculateDueDate(new \DateTimeImmutable('2016-08-03 10:12'), 8);
        $dueDate->shouldBeLike(new \DateTimeImmutable(('2016-08-04 10:12')));
    }
}
