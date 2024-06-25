<?php
// providing statistic for one single question

namespace quiz;


class Stats extends Model
{
    private int $id;
    private int $questionId;
    private int $userId;
    private int $timesAsked;
    private int $timesRight;


    /**
     * @param int $id
     * @param int $userId
     * @param int $questionId
     * @param int $timesAsked
     * @param int $timesRight
     */
    public function __construct(int $id,int $userId, int $questionId,int $timesAsked = 0, int $timesRight = 0)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->questionId = $questionId;
        $this->timesAsked = $timesAsked;
        $this->timesRight = $timesRight;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTimesAsked(): int
    {
        return $this->timesAsked;
    }

    public function getTimesRight(): int
    {
        return $this->timesRight;
    }

    public function incrementTimesAsked():void
    {
        $this->timesAsked++;
    }
    public function incrementTimesRight():void
    {
        $this->timesRight++;
    }

    public function reset():void
    {
        $this->timesAsked = 0;
        $this->timesRight = 0;
    }



}