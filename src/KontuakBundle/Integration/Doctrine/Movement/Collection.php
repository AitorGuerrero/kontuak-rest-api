<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\ORM\QueryBuilder;
use Kontuak\Movement;
use Kontuak\Movement\Id;
use Kontuak\PeriodicalMovement;
use Symfony\Component\Intl\Exception\NotImplementedException;

class Collection implements Movement\Collection
{

    /**
     * @var Source
     */
    private $source;

    private $repository;
    /** @var QueryBuilder */
    private $queryBuilder;
    private $result;

    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->repository = $this->source->em()->getRepository('KontuakBundle:Integration\Doctrine\Movement');
        $this->queryBuilder = $this->repository->createQueryBuilder('m');
    }

    public function orderByDateDesc()
    {
        $this->queryBuilder->orderBy('m.date', 'DESC');
        return $this;
    }

    /**
     * @param int $amount
     * @return Movement\Collection
     */
    public function limit($amount)
    {
        $this->queryBuilder->setMaxResults($amount);
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $this->queryBuilder
            ->andWhere('m.date < :dateLessThan')
            ->setParameter('dateLessThan', $date);
        return $this;
    }

    /**
     * @return float
     * @todo insert the sum in the query
     */
    public function amountSum()
    {
        $totalAmount = 0;
        foreach($this->collection() as $movement) {
            $totalAmount += $movement->amount();
        }

        return $totalAmount;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return Movement\Collection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $this->queryBuilder
            ->andWhere('m.created < :createdIsLessThan')
            ->setParameter('createdIsLessThan', $dateTime);
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $this->queryBuilder
            ->andWhere('m.date = :dateEqualTo')
            ->setParameter('dateEqualTo', $date);
        return $this;
    }

    /**
     * @return Movement\Collection
     */
    public function orderByDate()
    {
        $this->queryBuilder->orderBy('m.date', 'ASC');
        return $this;
    }

    /**
     * @return Movement[]
     */
    public function toArray()
    {
        return $this->collection();
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return Movement\Collection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->queryBuilder
            ->andWhere('m.periodicalMovementId = :periodicalMovementIs')
            ->setParameter('periodicalMovementIs', $periodicalMovement->id()->serialize());
        return $this;
    }

    /**
     * @param Id $id
     * @return \Kontuak\Movement
     */
    public function findById(Id $id)
    {
        throw new NotImplementedException('KontuakBundle\Integration\Doctrine\Movement\Collection:findById not implementd');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->collection());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->collection();
        next($this->result);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->collection());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $this->collection();
        return isset($this->result[key($this->result)]) !== false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->collection());
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->collection());
    }

    private function collection()
    {
        if (!$this->result) {
            $this->result = $this->queryBuilder
                ->getQuery()
                ->getResult();
        }
        return $this->result;
    }
}