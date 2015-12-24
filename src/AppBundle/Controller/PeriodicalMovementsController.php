<?php

namespace AppBundle\Controller;

use Kontuak\Ports\PeriodicalMovement\Create;
use Kontuak\Ports\PeriodicalMovement\Apply;
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
     *  }
     * )
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\Response
     */
    public function getPeriodical_movementsAction(HttpFoundation\Request $httpRequest)
    {
        $useCase = $this->get('kontuak.interactors.periodical_movement.get_all');
        $request = $useCase->newRequest();
        $limit = $httpRequest->get('limit');
        $page = $httpRequest->get('page');
        if ($limit) {
            $request->limit = $limit;
        }
        if ($page) {
            $request->page = $page;
        }

        return $this->handleView($this->view($useCase->execute($request)));
    }

    /**
     * @ApiDoc()
     * @param $id
     * @return HttpFoundation\Response
     */
    public function getPeriodical_movementAction($id)
    {
        $useCase = $this->get('kontuak.interactors.periodical_movement.get_one');
        $request = $useCase->newRequest();
        $request->id = $id;

        $response = $useCase->execute($request);

        return $this->handleView($this->view($response->periodicalMovement));
    }

    /**
     * @ApiDoc(
     *  input="AppBundle\Resources\Form\Type\PeriodicalMovement",
     *  output="AppBundle\Resources\Form\Resource\PeriodicalMovement"
     * )
     * @param HttpFoundation\Request $httpRequest
     * @throws \Kontuak\Ports\Exception\EntityNotFound
     * @return HttpFoundation\Response
     */
    public function postPeriodical_movementAction(HttpFoundation\Request $httpRequest)
    {
        $resource = new Form\Resource\PeriodicalMovement();
        $form = $this->createForm(new Form\Type\PeriodicalMovement(), $resource);
        $form->handleRequest($httpRequest);

        if (!$form->isValid()) {
            $view = $this->view($form);
            return $this->handleView($view);
        }
        $useCase = $this->get('kontuak.interactors.periodical_movement.create');
        $request = $useCase->newRequest();
        $request->id = $resource->id;
        $request->amount = $resource->amount;
        $request->concept = $resource->concept;
        $request->starts = $resource->starts;
        $request->periodAmount = $resource->period->amount;
        $request->periodType = $resource->period->type;

        $useCase->execute($request);
        $this->getDoctrine()->getEntityManager()->flush();

        $useCase = $this->get('kontuak.interactors.periodical_movement.get_one');
        $request = $useCase->newRequest();
        $request->id = $resource->id;
        $periodicalMovement = $useCase->execute($request);

        return $this->handleView($this->view($periodicalMovement->periodicalMovement));
    }

    /**
     * @ApiDoc(
     *  input="AppBundle\Resources\Form\Type\PeriodicalMovement\PatchPeriodicalMovement",
     *  output="AppBundle\Resources\Form\Resource\PeriodicalMovement"
     * )
     * @param $id
     * @param HttpFoundation\Request $httpRequest
     * @throws \Kontuak\Ports\Exception\EntityNotFound
     * @return HttpFoundation\Response
     */
    public function putPeriodical_movementAction($id, HttpFoundation\Request $httpRequest)
    {
        $resource = new Form\Resource\PeriodicalMovement();
        $form = $this->createForm(new Form\Type\PeriodicalMovement(), $resource);
        $form->handleRequest($httpRequest);

        if (!$form->isValid()) {
            $view = $this->view($form);
            return $this->handleView($view);
        }
        $useCase = $this->get('kontuak.interactors.periodical_movement.update');
        $request = $useCase->newRequest();
        $request->id = $id;
        $request->amount = $resource->amount;
        $request->concept = $resource->concept;
        $request->starts = $resource->starts;
        $request->periodAmount = $resource->period->amount;
        $request->periodType = $resource->period->type;

        $useCase->execute($request);
        $this->getDoctrine()->getEntityManager()->flush();

        $useCase = $this->get('kontuak.interactors.periodical_movement.get_one');
        $request = $useCase->newRequest();
        $request->id = $resource->id;
        $periodicalMovement = $useCase->execute($request);

        return $this->handleView($this->view($periodicalMovement));
    }
}
