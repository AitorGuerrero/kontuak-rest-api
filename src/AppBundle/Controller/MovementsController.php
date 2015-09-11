<?php

namespace AppBundle\Controller;

use AppBundle\Resources\Form;
use Kontuak\Movement\TotalAmountCalculator;
use KontuakBundle\Integration\Doctrine\Movement;
use Kontuak\Interactors;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class MovementsController
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController
{

    /**
     * @param HttpFoundation\Request $httpRequest
     * @throws Interactors\InvalidArgumentException
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "description"="How many objects to receive"
     *      }
     *  }
     * )
     */
    public function getMovementsHistoryAction(HttpFoundation\Request $httpRequest)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $source = new Movement\Source($em);
        $totalAmountCalculator = new TotalAmountCalculator($source);
        $useCase = new Interactors\Movement\History\UseCase($source, $totalAmountCalculator);
        $request = new Interactors\Movement\History\Request();
        $request->limit = (int) $httpRequest->get('limit');
        $useCaseResponse = $useCase->execute($request);

        $response = new HttpFoundation\JsonResponse($useCaseResponse->movements, 200);

        return $response;
    }

    /**
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true
     * )
     */
    public function getMovementsComingAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $source = new Movement\Source($em);
        $useCase = new Interactors\Movement\Coming\UseCase($source, new \DateTime(), new TotalAmountCalculator($source));
        $useCaseResponse = $useCase->execute();

        $response = new HttpFoundation\JsonResponse($useCaseResponse->movements, 200);

        return $response;
    }

    /**
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc(
     *  resource=true,
     *  output="|AppBundle\Resources\Form\Resource"
     * )
     */
    public function getMovementAction($id)
    {
        $source = new Movement\Source($this->getDoctrine()->getEntityManager());
        $useCase = new Interactors\Movement\GetOne\UseCase($source);
        $request = new Interactors\Movement\GetOne\Request();
        $request->id = $id;
        $response = $useCase->execute($request);

        $movementResource = new Form\Resource\Movement();
        $movementResource->id = $response->movement['id'];
        $movementResource->amount = $response->movement['amount'];
        $movementResource->concept = $response->movement['concept'];
        $movementResource->date = $response->movement['date'];

        $view = $this->view($movementResource);

        return $this->handleView($view);
    }

    /**
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\Movement",
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function postMovementAction(HttpFoundation\Request $httpRequest)
    {
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement(), $movementResource);
        $form->handleRequest($httpRequest);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $useCase = new Interactors\Movement\Create\UseCase(
                new Movement\Source($em),
                new \DateTime()
            );
            $request = new Interactors\Movement\Create\Request();
            $request->id =  $movementResource->id;
            $request->amount = $movementResource->amount;
            $request->concept = $movementResource->concept;
            $request->date = $movementResource->date;
            $useCase->execute($request);
            $em->flush();
            return new HttpFoundation\JsonResponse([
                'movement' => $movementResource
            ]);
        }
        $view = $this->view($form);
        return $this->handleView($view);
    }

    /**
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\Movement",
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function putMovementsAction(HttpFoundation\Request $httpRequest, $id)
    {
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement(), $movementResource, ['method' => 'PUT']);
        $form->handleRequest($httpRequest);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $useCase = new Interactors\Movement\Update\UseCase(
                new Movement\Source($em),
                new \DateTime()
            );
            $request = new Interactors\Movement\Update\Request();
            $request->id =  $id;
            $request->amount = $movementResource->amount;
            $request->concept = $movementResource->concept;
            $request->date = $movementResource->date;
            $useCase->execute($request);
            $em->flush();
            return new HttpFoundation\JsonResponse([
                'movement' => $movementResource
            ]);
        }
        $view = $this->view($form);
        return $this->handleView($view);
    }

    /**
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc()
     */
    public function deleteMovementAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $source = new Movement\Source($this->getDoctrine()->getEntityManager());
        $useCase = new Interactors\Movement\Remove\UseCase($source);
        $request = new Interactors\Movement\Remove\Request();
        $request->id = $id;
        $useCase->execute($request);
        $em->flush();

        $httpResponse = new HttpFoundation\Response();

        return $httpResponse;
    }

    /**
     * @ApiDoc()
     * @param null $id
     * @return HttpFoundation\Response
     */
    public function optionsMovementAction($id)
    {
        return new HttpFoundation\Response('', 200);
    }
}