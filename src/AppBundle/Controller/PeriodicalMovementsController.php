<?php

namespace AppBundle\Controller;

use Kontuak\Interactors\PeriodicalMovement\Create;
use Kontuak\Interactors\PeriodicalMovement\Apply;
use Symfony\Component\HttpFoundation;
use AppBundle\Resources\Form;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class PeriodicalMovementsController
 * @package AppBundle\Controller
 */
class PeriodicalMovementsController extends FOSRestController
{

    /**
     * @ApiDoc
     */
    public function putPeriodical_movementsGenerate_movementsAction()
    {
        $useCase = $this->get('kontuak.interactors.periodical_movement.apply.use_case');
        $useCase->execute();
        $this->getDoctrine()->getManager()->flush();

        return new HttpFoundation\Response();
    }
}