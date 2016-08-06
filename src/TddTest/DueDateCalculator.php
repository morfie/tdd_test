<?php

namespace TddTest;

class DueDateCalculator
{
    public function calculateDueDate(\DateTimeImmutable $submitDate, int $turnaroundTime): \DateTimeImmutable
    {
        if ($turnaroundTime < 0) {
            throw new \InvalidArgumentException('Turnaround time must be positive value');
        }

        $workingHours = range(9, 16);
        $workingDays = range(1, 5);
        
        if (FALSE === in_array($submitDate->format('G'), $workingHours)) {
            throw new \OutOfRangeException(sprintf('Problem can only be reported during working hours. Working hours: (%s)',
                implode(',', $workingHours)
            ));
        }

        $currentHours = 0;
        $dueDate = $submitDate;
        while ($turnaroundTime > $currentHours) {
            $dueDate = $dueDate->modify('+1 hour');
            if (FALSE === in_array($dueDate->format('G'), $workingHours)) {
                continue;
            }

            if (FALSE === in_array($dueDate->format('N'), $workingDays)) {
                continue;
            }

            $currentHours++;
        }
        return $dueDate;
    }
}
