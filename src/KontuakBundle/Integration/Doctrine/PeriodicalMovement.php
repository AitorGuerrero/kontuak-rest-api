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

    private static $periodTypeMapping = [
        self::TYPE_DAY => Period::TYPE_DAY,
        self::TYPE_MONTH_DAY => Period::TYPE_MONTH_DAY
    ];

    public static function fromPeriodicalMovement(\Kontuak\PeriodicalMovement $periodicalMovement)
    {
        return new self(
            $periodicalMovement->id(),
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $periodicalMovement->starts(),
            $periodicalMovement->period()
        );
    }

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