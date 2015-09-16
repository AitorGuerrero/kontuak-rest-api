<?php

namespace KontuakBundle\Integration\Transformer;

use Kontuak\PeriodicalMovement\Transformer;

class PeriodicalMovement implements Transformer
{
    /** @var Period */
    private $periodTransformer;

    public function __construct(Period $periodTransformer)
    {

        $this->periodTransformer = $periodTransformer;
    }

    public function toResource(\Kontuak\PeriodicalMovement $entity)
    {
        $resource = new \AppBundle\Resources\Form\Resource\PeriodicalMovement();
        $resource->id = $entity->id()->serialize();
        $resource->concept = $entity->concept();
        $resource->amount = $entity->amount();
        $resource->starts = $entity->starts()->format('Y-m-d');
        $resource->period = $this->periodTransformer->toResource($entity->period());

        return $resource;
    }
}