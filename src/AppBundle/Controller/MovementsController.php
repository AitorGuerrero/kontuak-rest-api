<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Kontuak\Interactors\CreateNewEntry;
use KontuakBundle\Integration\Doctrine\Movement;
use AppBundle\Resources\Form;
use Symfony\Component\HttpFoundation;

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
        $movementResource = new Form\Resource\Movement();
        $form = $this->createForm(new Form\Type\Movement(), $movementResource);
        $form->handleRequest($httpRequest);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $useCase = new CreateNewEntry\UseCase(
                new Movement\Source($em),
                new \DateTime()
            );
            $request = new CreateNewEntry\Request();
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

        return $this->handleView($this->view($form));
    }
}