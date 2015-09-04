<?php

namespace AppBundle\Controller;

use AppBundle\Resources\Form;
use KontuakBundle\Integration\Doctrine\Movement;
use Kontuak\Interactors;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class MovementsController
 * @route
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param HttpFoundation\Request $httpRequest
     * @throws \Kontuak\Interactors\InvalidArgumentException
     * @throws \Kontuak\Interactors\SystemException
     * @internal param HttpFoundation\Request $request
     * @return HttpFoundation\JsonResponse
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\Movement",
     *  output="\AppBundle\Resources\Form\Type\Movement"
     * )
     */
    public function postAction(HttpFoundation\Request $httpRequest)
    {
        $webClient = 'http://localhost:3000';
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
            $response->headers->set('Access-Control-Allow-Origin', $webClient);
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
        $view->setHeader('Access-Control-Allow-Origin', $webClient);
        return $this->handleView($view);
    }
}