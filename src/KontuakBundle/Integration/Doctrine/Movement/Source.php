<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\Common\Persistence\ObjectManager;
use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Movement;
use Kontuak\Movement\Id;
use KontuakBundle\Integration\Doctrine\Movement as DoctrineMovement;

class Source implements Movement\Source
{
    /** @var ObjectManager */
    private $em;
    private $repository;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
        $this->repository = $this
            ->em
            ->getRepository('KontuakBundle:Integration\Doctrine\Movement');
    }

    /**
     * @return Movement\Collection
     */
    public function collection()
    {
        return new Collection(
            $this->repository->createQueryBuilder('m')
        );
    }

    /**
     * @param Movement $movement
     * @return mixed|void
     */
    public function add(Movement $movement)
    {
        $this->em->persist(DoctrineMovement::fromMovement($movement));
    }

    public function em() {
        return $this->em;
    }

    /**
     * @param Movement $movement
     * @return void
     */
    public function remove(Movement $movement)
    {
        $this->em->remove($movement);
    }

    public function persist(Movement $movement)
    {
        $this->em->persist($movement);
    }

    /**
     * @param Id $id
     * @return Movement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $id)
    {
        $movement = $this->repository->find($id->toString());
        if (null === $movement) {
            throw new EntityNotFound();
        }
        return $movement;
    }
}