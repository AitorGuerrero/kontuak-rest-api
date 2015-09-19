<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\Common\Persistence\ObjectManager;
use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Movement;
use Kontuak\Movement\Id;

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
     */
    public function add(Movement $movement)
    {
        $this->em->persist($movement);
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
     * @return Movement\Id
     */
    public function newId()
    {
        return new Movement\Id($this->uuidv4());
    }

    private function uuidv4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @param Id $id
     * @return Movement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $id)
    {
        $movement = $this->repository->find($id->serialize());
        if (false === $movement) {
            throw new EntityNotFound();
        }
        return $movement;
    }
}