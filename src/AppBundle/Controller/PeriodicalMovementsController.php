<?php

namespace AppBundle\Controller;

use Kontuak\Interactors\PeriodicalMovement\Create;
use Kontuak\Interactors\PeriodicalMovement\Apply;
use Kontuak\PeriodicalMovement\MovementsGenerator;
use Kontuak\Movement\Id\Generator;
use KontuakBundle\Integration\Doctrine\PeriodicalMovement\Source;
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
     *  input="\AppBundle\Resources\Form\Type\PeriodicalMovement"
     * )
     * @param $id
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     */
    public function postPeriodical_movementsAction($id, HttpFoundation\Request $httpRequest)
    {
        $mapPeriodTypeFromResourceToUSeCase = [
            Form\Resource\PeriodicalMovement\Period::TYPE_DAYS => Create\Request::TYPE_DAYS,
            Form\Resource\PeriodicalMovement\Period::TYPE_MONTHS => Create\Request::TYPE_MONTHS,
        ];
        $resource = new Form\Resource\PeriodicalMovement();
        $resource->period = new Form\Resource\PeriodicalMovement\Period();
        $form = $this->createForm(new Form\Type\PeriodicalMovement(), $resource);
        $form->handleRequest($httpRequest);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $useCase = new Create\UseCase(new Source($em));
            $request = new Create\Request();
            $request->id = $id;
            $request->amount = $resource->amount;
            $request->concept = $resource->concept;
            $request->periodAmount = $resource->period->amount;
            $request->periodType = $mapPeriodTypeFromResourceToUSeCase[$resource->period->type];
            $request->starts = $resource->starts;
            $useCase->execute($request);
            $em->flush();
            return new HttpFoundation\JsonResponse([
                'periodical_movement' => $resource
            ]);
        }
        $view = $this->view($form);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc
     */
    public function putPeriodical_movementsGenerate_movementsAction()
    {
        $timeStamp = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $movementsSource = new \KontuakBundle\Integration\Doctrine\Movement\Source($em);
        $periodicalMovementsSource = new Source($em);
        $movementsGenerator = new MovementsGenerator($movementsSource, new Generator(), $timeStamp);
        $useCase = new Apply\UseCase($movementsSource, $periodicalMovementsSource, $timeStamp, $movementsGenerator);
        $useCase->execute();
        $em->flush();

        return new HttpFoundation\Response();
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  input="\AppBundle\Resources\Form\Type\PeriodicalMovement"
     * )
     * @param $id
     * @param HttpFoundation\Request $httpRequest
     * @return HttpFoundation\JsonResponse
     */
    public function putPeriodical_movementsAction($id, HttpFoundation\Request $httpRequest)
    {
    }

    /**
     * @ApiDoc(resource=true)
     * @param $id
     */
    public function deletePeriodical_movementsAction($id)
    {

    }

    /**
     * @ApiDoc(resource=true)
     */
    public function getPeriodical_movementsAction($id)
    {

    }
}