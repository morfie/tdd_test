<?php

namespace TddTest;

class DueDateCalculator {

    protected $workingHours;

    protected $workingDays;

    public function __construct() {
        $this->workingDays = range(1, 5);
        $this->workingHours = range(9, 16);
    }

    public function calculateDueDate(\DateTimeImmutable $submitDate, int $turnaroundTime): \DateTimeImmutable {
        $this->validateTurnaroundTime($turnaroundTime);
        $this->validateSubmitDate($submitDate);

        $currentHours = $currentDays = 0;
        $remainderHours = $turnaroundTime % 8;
        $dueDate = $submitDate;
        while ($remainderHours > $currentHours) {
            $dueDate = $dueDate->modify('+1 hour');
            if (FALSE === in_array($dueDate->format('G'), $this->workingHours)) {
                continue;
            }

            if (FALSE === in_array($dueDate->format('N'), $this->workingDays)) {
                continue;
            }

            $currentHours++;
        }

        $turnaroundDays = floor($turnaroundTime / 8);
        while ($turnaroundDays > $currentDays) {
            $dueDate = $dueDate->modify('+1 day');
            if (FALSE === in_array($dueDate->format('N'), $this->workingDays)) {
                continue;
            }

            $currentDays++;
        }

        return $dueDate;
    }

    protected function validateTurnaroundTime(int $turnaroundTime) {
        if ($turnaroundTime <= 0) {
            throw new \InvalidArgumentException('Turnaround time must be positive value');
        }
    }

    protected function validateSubmitDate(\DateTimeImmutable $submitDate) {
        if (FALSE === in_array($submitDate->format('G'), $this->workingHours)) {
            throw new \OutOfRangeException(sprintf('Problem can only be reported during working hours. Working hours: (%s)',
                implode(',', $this->workingHours)
            ));
        }

        if (FALSE === in_array($submitDate->format('N'), $this->workingDays)) {
            throw new \OutOfRangeException(sprintf('Problem can only be reported during working days. Working days: (%s)',
                implode(',', $this->workingHours)
            ));
        }
    }
}
