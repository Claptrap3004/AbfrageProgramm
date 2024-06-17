<?php
// providing statistic for one single question

namespace quiz;

class Stats
{
    private int $id;
    private int $timesAsked;
    private int $timesRight;

    /**
     * @param int $id
     * @param int $timesAsked
     * @param int $timesRight
     */
    public function __construct(int $id, int $timesAsked = 0, int $timesRight = 0)
    {
        $this->id = $id;
        $this->timesAsked = $timesAsked;
        $this->timesRight = $timesRight;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTimesAsked(): int
    {
        return $this->timesAsked;
    }

    public function setTimesAsked(int $timesAsked): void
    {
        $this->timesAsked = $timesAsked;
    }

    public function getTimesRight(): int
    {
        return $this->timesRight;
    }

    public function setTimesRight(int $timesRight): void
    {
        $this->timesRight = $timesRight;
    }

    public function incrementTimesAsked():void
    {
        $this->timesAsked++;
    }
    public function incrementTimesRight():void
    {
        $this->timesRight++;
    }



}