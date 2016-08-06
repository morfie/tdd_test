<?php

namespace TddTest;

class DueDateCalculator {

    const WORKING_HOURS = 8;

    protected $workingHours;

    protected $workingDays;

    public function __construct() {
        $this->workingDays = range(1, 5);
        $this->workingHours = range(9, 16);
    }

    public function calculateDueDate(\DateTimeImmutable $submitDate, int $turnaroundTime): \DateTimeImmutable {
        $this->validateTurnaroundTime($turnaroundTime);
        $this->validateSubmitDate($submitDate);

        $dueDate = $this->addHours($submitDate, $turnaroundTime);
        $dueDate = $this->addDays($dueDate, $turnaroundTime);

        return $dueDate;
    }

    protected function validateTurnaroundTime(int $turnaroundTime) {
        if ($turnaroundTime <= 0) {
            throw new \InvalidArgumentException('Turnaround time must be positive value');
        }
    }

    protected function validateSubmitDate(\DateTimeImmutable $submitDate) {
        if (FALSE === $this->isWorkingHour($submitDate)) {
            throw new \OutOfRangeException(sprintf('Problem can only be reported during working hours. Working hours: (%s)',
                implode(',', $this->workingHours)
            ));
        }

        if (FALSE === $this->isWorkingDay($submitDate)) {
            throw new \OutOfRangeException(sprintf('Problem can only be reported during working days. Working days: (%s)',
                implode(',', $this->workingHours)
            ));
        }
    }

    protected function addHours(\DateTimeImmutable $dueDate, int $turnaroundTime): \DateTimeImmutable {
        $currentHours = 0;
        $remainderHours = $turnaroundTime % self::WORKING_HOURS;
        while ($remainderHours > $currentHours) {
            $dueDate = $dueDate->modify('+1 hour');
            if (FALSE === $this->isWorkingHour($dueDate)) {
                continue;
            }

            if (FALSE === $this->isWorkingDay($dueDate)) {
                continue;
            }

            $currentHours++;
        }

        return $dueDate;
    }

    protected function addDays(\DateTimeImmutable $dueDate, int $turnaroundTime): \DateTimeImmutable {
        $currentDays = 0;
        $turnaroundDays = floor($turnaroundTime / self::WORKING_HOURS);
        while ($turnaroundDays > $currentDays) {
            $dueDate = $dueDate->modify('+1 day');
            if (FALSE === $this->isWorkingDay($dueDate)) {
                continue;
            }

            $currentDays++;
        }

        return $dueDate;
    }

    protected function isWorkingHour(\DateTimeImmutable $dueDate): bool {
        return in_array($dueDate->format('G'), $this->workingHours);
    }

    protected function isWorkingDay(\DateTimeImmutable $dueDate): bool {
        return in_array($dueDate->format('N'), $this->workingDays);
    }
}
