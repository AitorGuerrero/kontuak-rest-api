<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Period;
use Kontuak\PeriodicalMovement as BasePeriodicalMovement;

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

    private $periodTypeMapping = [
        self::TYPE_DAY => Period::TYPE_DAY,
        self::TYPE_MONTH_DAY => Period::TYPE_MONTH_DAY
    ];

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
        $this->periodType = array_flip($this->periodTypeMapping)[$this->period()->type()];
        $this->periodAmount = $this->period()->amount();
    }
}