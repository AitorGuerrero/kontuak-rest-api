<?php

namespace AppBundle\Controller;

use AppBundle\Resources\Form;
use Kontuak\Movement\TotalAmountCalculator;
use KontuakBundle\Integration\Doctrine\Movement;
use Kontuak\Interactors;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class MovementsController
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\Movement",
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function postAction(HttpFoundation\Request $httpRequest)
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
            $response = new HttpFoundation\Response();
//            $response->headers->set(
//                'Location',
//                $this->generateUrl(
//                    'get_movements',
//                    ['id' => $movementResource->id],
//                    UrlGenerator::ABSOLUTE_URL
//                )
//            );
            $em->flush();
            return $response;
        }
        $view = $this->view($form);
        return $this->handleView($view);
    }

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
    public function getHistoryAction(HttpFoundation\Request $httpRequest)
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
     * @param $id
     * @return HttpFoundation\Response
     * @ApiDoc()
     */
    public function deleteAction($id)
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
    public function optionsAction($id)
    {
        return new HttpFoundation\Response('', 200);
    }
}