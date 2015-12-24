<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Exception\IncorrectResourceId;
use AppBundle\Resources\Form;
use KontuakBundle\Integration\Doctrine\Movement;
use Kontuak\Ports;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* ANNOTATIONS */
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class MovementsController
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController
{
    /**
     * @param HttpFoundation\Request $httpRequest
     * @throws Ports\InvalidArgumentException
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  parameters= {
     *      {
     *          "name"="date-from",
     *          "dataType"="date",
     *          "required"=false
     *      },
     *      {
     *          "name"="date-to",
     *          "dataType"="date",
     *          "required"=false
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"=false,
     *          "description"="How many objects to receive"
     *      }
     *  },
     *  output="AppBundle\Resources\Form\Resource\Movement"
     * )
     */
    public function getMovementsHistoryAction(HttpFoundation\Request $httpRequest)
    {
        $useCase = $this->get('kontuak.interactors.movement.history');
        $request = new Ports\Movement\History\Request();
        $request->limit = (int) $httpRequest->get('limit');
        $request->toDate = $httpRequest->get('dateTo');
        $request->fromDate = $httpRequest->get('dateFrom');
        $amounts = $useCase->execute($request);

        return $this->handleView($this->view($amounts));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  output="AppBundle\Resources\Form\Resource\MovementsCollection",
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
     * @throws Ports\Exception\InvalidArgument
     * @return array
     */
    public function getMovementsAction(HttpFoundation\Request $httpRequest)
    {
        $useCase = $this->get('kontuak.interactors.movement.get_all');
        $request = $useCase->newRequest();
        $request->limit = (int)$httpRequest->get('limit');
        $request->page = (int)$httpRequest->get('page');
        $movements = $useCase->execute($request);
        $movementResources = [];
        foreach ($movements as $movement) {
            $movementResources[] = new Form\Resource\Movement($movement);
        }

        return new HttpFoundation\JsonResponse(['movements' => $movementResources]);
    }

    /**
     * @ApiDoc(
     *  input="AppBundle\Resources\Form\Type\Movement",
     *  output="AppBundle\Resources\Form\Resource\Movement"
     * )
     * @param $id
     * @param HttpFoundation\Request $httpRequest
     * @throws IncorrectResourceId
     * @throws Ports\Exception\EntityNotFound
     * @throws Ports\MovementDoesNotExistException
     * @return HttpFoundation\Response
     */
    public function putMovementAction($id, HttpFoundation\Request $httpRequest)
    {
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement(), $movementResource, ['method'=>'PUT']);
        $form->handleRequest($httpRequest);

        if (!$form->isValid()) {
            return $this->handleView(
                $this->view(
                    $form,
                    HttpFoundation\Response::HTTP_BAD_REQUEST
                )
            );
        }
        $exists = $this->get('kontuak.interactors.movement.exists')->execute($id);
        $this->get('kontuak.interactors.movement.put')->execute(
            $id,
            $movementResource->amount,
            $movementResource->concept,
            $movementResource->date
        );
        $this->getDoctrine()->getManager()->flush();
        $movement = $this->get('kontuak.interactors.movement.get_one')->execute($id);

        return $this->handleView($this->view(
            $movement,
            $exists ? HttpFoundation\Response::HTTP_OK : HttpFoundation\Response::HTTP_CREATED
        ));
    }

    /**
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc(
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function getMovementAction($id)
    {
        try {
            $movement = $this->get('kontuak.interactors.movement.get_one')->execute($id);
        } catch (Ports\Exception\EntityNotFound $exception) {
            return new HttpFoundation\Response('Movement not found', HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        $movementResource = new Form\Resource\Movement($movement);

        return $this->handleView($this->view($movementResource));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc()
     */
    public function deleteMovementAction($id)
    {
        $useCase = $this->get('kontuak.interactors.movement.remove');
        $request = new Ports\Movement\Remove\Request();
        $request->id = $id;
        $useCase->execute($request);
        $this->getDoctrine()->getEntityManager()->flush();

        return new HttpFoundation\Response();
    }
}