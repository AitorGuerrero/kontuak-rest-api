<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Period;
use Kontuak\PeriodicalMovement as BasePeriodicalMovement;
use Kontuak\PeriodicalMovement\Id;

class PeriodicalMovement extends BasePeriodicalMovement
{
    const TYPE_DAY = 1;
    const TYPE_MONTH_DAY = 2;

    /** @var string */
    protected $doctrineId;

    /** @var int */
    protected $periodType;

    /** @var int */
    protected $periodAmount;

    public function __construct(
        Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    ) {
        parent::__construct($id, $amount, $concept, $starts, $period);
    }

    private static $periodTypeMapping = [
        self::TYPE_DAY => Period::TYPE_DAY,
        self::TYPE_MONTH_DAY => Period::TYPE_MONTH_DAY
    ];

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
        $this->periodType = array_flip(self::$periodTypeMapping)[$this->period()->type()];
        $this->periodAmount = $this->period()->amount();
    }

    public function mapToDomain()
    {
        $period = null;
        $this->id = new BasePeriodicalMovement\Id($this->doctrineId);
        switch ($this->periodType) {
            case self::TYPE_DAY:
                $period = new Period\DaysPeriod($this->periodAmount);
                break;
            case self::TYPE_MONTH_DAY:
                $period = new Period\MonthDayPeriod($this->periodAmount);
                break;
        }
        $this->period = $period;
    }
}