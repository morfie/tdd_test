<?php

namespace TddTest;

class DueDateCalculator
{
    public function calculateDueDate(\DateTimeImmutable $submitDate, int $turnaroundTime): \DateTimeImmutable
    {
        if ($turnaroundTime < 0) {
            throw new \InvalidArgumentException('Turnaround time must be positive value');
        }
        //TODO must be submit date in working hours

        $currentHours = 0;
        $dueDate = $submitDate;
        $workingHours = range(9, 16);
        $workingDays = range(1, 5);
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
