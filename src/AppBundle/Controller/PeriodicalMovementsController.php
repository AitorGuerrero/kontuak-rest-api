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
     * Returns a list of periodical movements
     * @ApiDoc(
     *  resource=true,
     *  parameters={
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"=true
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"=true
     *      }
     *  },
     *  tags={"not implemented"}
     * )
     */
    public function getPeriodical_movementsAction()
    {

    }

    /**
     * @ApiDoc(tags={"not implemented"})
     * @param $id
     */
    public function getPeriodical_movementAction($id)
    {

    }

    /**
     * Saves a new periodical movement
     * @ApiDoc(
     *  tags={"not implemented"},
     *  input="AppBundle\Resources\Form\Type\PeriodicalMovement\NewPeriodicalMovement",
     *  output="AppBundle\Resources\Form\Resource\PeriodicalMovement"
     * )
     */
    public function postPeriodical_movementAction()
    {

    }

    /**
     * Form required to post a new periodical movement
     * @ApiDoc(
     *  tags={"not implemented"},
     *  output="AppBundle\Resources\Form\Type\PeriodicalMovement\NewPeriodicalMovement"
     * )
     */
    public function getPeriodical_movementsFormPostAction()
    {

    }

    /**
     * @ApiDoc(
     *  input="AppBundle\Resources\Form\Type\PeriodicalMovement\PatchPeriodicalMovement",
     *  output="AppBundle\Resources\Form\Resource\PeriodicalMovement",
     *  tags={"not implemented"}
     * )
     * @param $id
     */
    public function putPeriodical_movementAction($id)
    {

    }

    /**
     * @ApiDoc(tags={"not implemented"})
     * @param $id
     */
    public function deletePeriodical_movementAction($id)
    {

    }

    /**
     * @ApiDoc(
     *  input="AppBundle\Resources\Form\Type\PeriodicalMovement\PatchPeriodicalMovement",
     *  output="AppBundle\Resources\Form\Resource\PeriodicalMovement",
     *  tags={"not implemented"}
     * )
     * @param $id
     */
    public function patchPeriodical_movementsAction($id)
    {
    }

    /**
     * Form to patch a periodical movement
     * @ApiDoc(
     *  output="AppBundle\Resources\Form\Type\PeriodicalMovement\PatchPeriodicalMovement",
     *  tags={"not implemented"}
     * )
     */
    public function getPeriodical_movementsFormPatchAction()
    {
    }

    /**
     * @ApiDoc(tags={"not implemented"})
     * @param $id
     */
    public function getPeriodical_movementMovementsAction($id)
    {

    }
}