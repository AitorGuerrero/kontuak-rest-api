<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Exception\ControllerNotImplemented;
use AppBundle\Resources\Form;
use Kontuak\Movement\TotalAmountCalculator;
use Kontuak\PeriodicalMovement\Id\Generator;
use Kontuak\PeriodicalMovement\MovementsGenerator;
use KontuakBundle\Integration\Doctrine\Movement;
use Kontuak\Interactors;
use KontuakBundle\Integration\Doctrine\PeriodicalMovement\Source;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MovementsController
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  output="\AppBundle\Resources\Form\Type\Movement",
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
     * @throws Interactors\Exception\InvalidArgument
     * @return array
     */
    public function getMovementsAction(HttpFoundation\Request $httpRequest)
    {
        $useCase = $this->get('kontuak.interactors.movement.get_all.use_case');
        $request = $useCase->newRequest();
        $request->limit = (int) $httpRequest->get('limit');
        $request->page = (int) $httpRequest->get('page');
        $response = $useCase->execute($request);

        return new HttpFoundation\JsonResponse(['movements' => $response->movements]);
    }

    /**
     * @ApiDoc(
     *  output="\AppBundle\Resources\Form\Type\Movement\UpdateMovement",
     *  tags={"not implemented"}
     * )
     */
    public function getMovementsFormPatchAction()
    {
        throw new ControllerNotImplemented();
    }

    /**
     * @ApiDoc(
     *  input = "AppBundle\Resources\Form\Type\Movement\UpdateMovement",
     *  tags={"not implemented"}
     * )
     * @param $id
     * @throws ControllerNotImplemented
     */
    public function patchMovementAction($id)
    {
        throw new ControllerNotImplemented();
    }

    /**
     * @ApiDoc(
     *  output="\AppBundle\Resources\Form\Type\Movement\NewMovement",
     *  tags={"not implemented"}
     * )
     */
    public function getMovementsFormPostAction()
    {
        throw new ControllerNotImplemented();
    }

    /**
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  input="\AppBundle\Resources\Form\Type\Movement\NewMovement",
     *  output="\AppBundle\Resources\Form\Resource\Movement"
     * )
     */
    public function postMovementAction(HttpFoundation\Request $httpRequest)
    {
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement\NewMovement(), $movementResource);
        $form->handleRequest($httpRequest);

        if (!$form->isValid()) {
            $view = $this->view($form);
            return $this->handleView($view);
        }
        $useCase = $this->get('kontuak.interactors.movement.create');
        $request = new Interactors\Movement\Create\Request();
        $request->id = $movementResource->id;
        $request->amount = $movementResource->amount;
        $request->concept = $movementResource->concept;
        $request->date = $movementResource->date;
        $movement = $useCase->execute($request);
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->handleView($this->view([$form->getName() => $movement]));
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
        $useCase = $this->get('kontuak.interactors.movement.get_one');
        $request = new Interactors\Movement\GetOne\Request();
        $request->id = $id;
        $response = $useCase->execute($request);

        $movementResource = new Form\Resource\Movement();
        $movementResource->id = $response->movement['id'];
        $movementResource->amount = $response->movement['amount'];
        $movementResource->concept = $response->movement['concept'];
        $movementResource->date = $response->movement['date'];

        return $this->handleView($this->view(['movement' => $movementResource]));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param $id
     * @throws Interactors\Movement\Remove\MovementDoesNotExistsException
     * @return HttpFoundation\Response
     * @ApiDoc()
     */
    public function deleteMovementAction($id)
    {
        $useCase = $this->get('kontuak.interactors.movement.remove');
        $request = new Interactors\Movement\Remove\Request();
        $request->id = $id;
        $useCase->execute($request);
        $this->getDoctrine()->getEntityManager()->flush();

        return new HttpFoundation\Response();
    }

    /**
     * @param HttpFoundation\Request $httpRequest
     * @throws Interactors\InvalidArgumentException
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
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function getMovementsHistoryAction(HttpFoundation\Request $httpRequest)
    {
        $useCase = $this->get('kontuak.interactors.movement.history');
        $request = new Interactors\Movement\History\Request();
        $request->limit = (int) $httpRequest->get('limit');
        $useCaseResponse = $useCase->execute($request);

        return $this->handleView($this->view($useCaseResponse->amounts));
    }
}