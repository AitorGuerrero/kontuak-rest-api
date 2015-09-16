<?php

namespace AppBundle\Controller;

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
     * @param HttpFoundation\Request $httpRequest
     * @throws Interactors\InvalidArgumentException
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
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
        $useCase = $this->get('kontuak.interactors.movement.history.use_case');
        $request = new Interactors\Movement\History\Request();
        $request->limit = (int) $httpRequest->get('limit');
        $useCaseResponse = $useCase->execute($request);

        $response = new HttpFoundation\JsonResponse($useCaseResponse->movements, 200);

        return $response;
    }

    /**
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     * )
     */
    public function getMovementsComingAction()
    {
        $useCase = $this->get('kontuak.interactors.movement.coming.use_case');
        $request = new Interactors\Movement\Coming\Request();
        $request->limit = 3;
        $useCaseResponse = $useCase->execute($request);

        $response = new HttpFoundation\JsonResponse($useCaseResponse->movements, 200);

        return $response;
    }

    /**
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc(
     *  resource=true,
     *  output="\AppBundle\Resources\Form\Type\CompleteMovement"
     * )
     */
    public function getMovementAction($id)
    {
        $useCase = $this->get('kontuak.interactors.movement.get_one.use_case');
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
     *  input="\AppBundle\Resources\Form\Type\NewMovement",
     *  output="\AppBundle\Resources\Form\Type\CompleteMovement"
     * )
     */
    public function postMovementAction($id, HttpFoundation\Request $httpRequest)
    {
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement(), $movementResource);
        $form->handleRequest($httpRequest);

        if ($form->isValid()) {
            $useCase = $this->get('kontuak.interactors.movement.create.use_case');
            $request = $this->get('kontuak.interactors.movement.create.request');
            $request->id =  $id;
            $request->amount = $movementResource->amount;
            $request->concept = $movementResource->concept;
            $request->date = $movementResource->date;
            $period = $httpRequest->get('period');
            if($period) {
                $request->isPeriodical = true;
                $request->periodType = $period['type'];
                $request->periodAmount = $period['amount'];
            }
            $response = $useCase->execute($request);
            $this->getDoctrine()->getEntityManager()->flush();
            $movementResource->amount = $response->movementAmount;
            $movementResource->concept = $response->movementConcept;
            $movementResource->date = $response->movementDate;
            $movementResource->id = $response->movementId;
            if ($response->periodicalMovementId) {
                $periodResource = new Form\Resource\PeriodicalMovement\Period();
                $periodResource->amount = $response->periodicalMovementAmount;
                $periodResource->type = $response->periodicalMovementType;
                $periodicalMovementResource = new Form\Resource\PeriodicalMovement();
                $periodicalMovementResource->id = $response->periodicalMovementId;
                $periodicalMovementResource->amount = $response->movementAmount;
                $periodicalMovementResource->concept = $response->movementConcept;
                $periodicalMovementResource->starts = $response->movementDate;
                $periodicalMovementResource->period = $periodResource;
                $movementResource->periodicalMovement = $periodicalMovementResource;
            }

            return new HttpFoundation\JsonResponse([
                $movementResource
            ]);
        }
        $view = $this->view($form);
        return $this->handleView($view);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\Movement",
     *  output="\AppBundle\Resources\Form\Type\CompleteMovement"
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
     * @ApiDoc(
     *  output="\AppBundle\Resources\Form\Type\CompleteMovement"
     * )
     */
    public function deleteMovementAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $useCase = $this->get('kontuak.interactors.movement.remove.use_case');
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