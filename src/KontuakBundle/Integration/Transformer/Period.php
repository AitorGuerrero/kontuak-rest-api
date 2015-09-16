<?php

namespace KontuakBundle\Integration\Transformer;

class Period 
{
    /**
     * @param \Kontuak\Period $period
     * @return \Kontuak\Period
     */
    public function toResource(\Kontuak\Period $period)
    {
        $resource = new \AppBundle\Resources\Form\Resource\PeriodicalMovement\Period();
        $resource->amount = $period->amount();
        $resource->type = $period->type();

        return $period;
    }
}