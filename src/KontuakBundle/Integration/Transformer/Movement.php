<?php

namespace KontuakBundle\Integration\Transformer;

class Movement 
{
    /** @var PeriodicalMovement */
    private $periodicalMovementTransformer;

    public function __construct(PeriodicalMovement $periodicalMovementTransformer)
    {
        $this->periodicalMovementTransformer = $periodicalMovementTransformer;
    }

    public function toResource(\Kontuak\Movement $movement)
    {
        $resource = new \AppBundle\Resources\Form\Resource\Movement();
        $resource->amount = $movement->amount();
        $resource->id = $movement->id()->serialize();
        $resource->concept = $movement->concept();
        $resource->date = $movement->date()->format('Y-m-d');
        if($movement->periodicalMovement()) {
            $resource->periodicalMovement = $this
                ->periodicalMovementTransformer
                ->toResource($movement->periodicalMovement())
            ;
        }
    }
}