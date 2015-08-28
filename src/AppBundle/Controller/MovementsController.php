<?php

namespace AppBundle\Controller;

use AppBundle\Integration\AitorGuerrero\Kontuak\MovementsCollection;
use FOS\RestBundle\Controller\FOSRestController;
use Kontuak\Interactors\CreateNewEntry;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class MovementsController
 * @package AppBundle\Controller
 */
class MovementsController extends FOSRestController
{
    /**
     * @route("/movements/{id}")
     * @method("GET")
     * @ApiDoc()
     */
    public function getAction()
    {
        $movementsCollection = new MovementsCollection($this->getDoctrine()->getManager());
        $request = new CreateNewEntry\Request();
        $request->amount = 10;
        $useCase = new CreateNewEntry\UseCase($movementsCollection);
        $response = $useCase->execute($request);
        $view = $this->view($response, 200);
        return $this->handleView($view);
    }
}