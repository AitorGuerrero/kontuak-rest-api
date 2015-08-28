<?php

namespace AppBundle\Tests\Integration\AitorGuerrero\Kontuak;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Integration\AitorGuerrero\Kontuak\MovementsCollection;
use Kontuak\Tests\Implementation\MovementsCollectionTest as MovementsCollectionTestTrait;

class MovementsCollectionTest extends KernelTestCase
{
    use MovementsCollectionTestTrait;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $this->collection = new MovementsCollection($this->em);
        $this->timeStamp = new \DateTime('2015-09-10 00:00:00');
    }
}