<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;

class PeriodicalMovement extends \Kontuak\PeriodicalMovement
{
    const TYPE_DAY = 1;
    const TYPE_MONTH_DAY = 2;

    private static $typeMapToDomain = [
        self::TYPE_DAY => Period::TYPE_DAY,
        self::TYPE_MONTH_DAY => Period::TYPE_MONTH_DAY,
    ];

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
        $this->id = new Id($this->doctrineId);
        $this->period = Period::factory(
            self::$typeMapToDomain[$this->periodType],
            $this->periodAmount
        );
    }
}