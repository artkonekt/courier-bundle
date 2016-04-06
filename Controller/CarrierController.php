<?php
/**
 * Contains class CarrierController
 *
 * @package     Artkonekt\SyliusShippingBundle\Controller
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-05
 * @version     2016-04-05
 */

namespace Konekt\CourierBundle\Controller;

use Exception;
use Konekt\Courier\Sprinter\SprinterGateway;
use Konekt\CourierBundle\Event\AwbCreatedEvent;
use Konekt\CourierBundle\Event\AwbDeletedEvent;
use Konekt\CourierBundle\Event\CourierEvents;
use Konekt\CourierBundle\Services\AwbCreatorFormFactoryInterface;
use Konekt\CourierBundle\Services\ModelFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarrierController extends Controller
{
    public function createAwbAction(Request $request, $shipmentId)
    {
        $shipmentRepository = $this->get('sylius.repository.shipment');
        $shipment = $shipmentRepository->find($shipmentId);

        /** @var ModelFactoryInterface $modelFactory */
        $modelFactory = $this->get('konekt_courier.model_factory');
        $model = $modelFactory->create($shipment->getCarrier(), $shipment);

        /** @var AwbCreatorFormFactoryInterface $formFactory */
        $formFactory = $this->get('konekt_courier.awb_creator_form_factory');
        list($form, $formTemplate) = $formFactory->createForm($shipment->getCarrier(), $model, ['action' => $this->generateUrl('konekt_courier_create_awb', ['shipmentId' => $shipmentId])]);

        $form->handleRequest($request);

        $response = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $engine = $this->get('konekt_courier.engine');
            $response = $engine->createAwb($shipment->getCarrier(), $model);

            if ($response->isSuccess()) {
                $awbNumber = $response->getAwbNumber();

                $event = new AwbCreatedEvent($shipmentId, $awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);

                $carrierGateway = new SprinterGateway();
                return $this->render('KonektCourierBundle:Awb:created.html.twig', compact('awbNumber', 'carrierGateway'));
            }
        }

        return $this->render($formTemplate, array(
            'form' => $form->createView(),
            'result' => $response
        ));
    }

    /**
     * Shows the details of an AWB.
     *
     * @param $awbNumber
     *
     * @return Response
     */
    public function showDetailsAction($awbNumber)
    {
        $carrierGateway = new SprinterGateway();
        return $this->render('KonektCourierBundle:Awb:details.html.twig', compact('awbNumber', 'carrierGateway'));
    }

    /**
     * Deletes an AWB.
     *
     * @param $awbNumber
     *
     * @return Response
     * @throws Exception
     */
    public function deleteAwbAction($awbNumber)
    {
        $engine = $this->get('konekt_courier.engine');

        try {

            $response = $engine->deleteAwb('sprinter', $awbNumber);
            $error = false;
            if ($response->isSuccess()) {
                $event = new AwbDeletedEvent($awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_DELETED, $event);
            } else {
                $error = $response->getErrorMessage();
            }

            return $this->render('KonektCourierBundle:Awb:delete_result.html.twig', compact('awbNumber', 'error'));
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    public function showLabelPdfAction($awbNumber)
    {
        $engine = $this->get('konekt_courier.engine');

        try {
            $response = $engine->showPdfLabel('sprinter', $awbNumber);
            $pdf = $response->getPdf();

            //Do not print alongside HTML result (will fail to load PDF)
            $response = new Response($pdf);
            $response->headers->set('Content-Type', 'application/pdf');

            return $response;
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    public function showLabelHtmlAction($awbNumber)
    {
        $engine = $this->get('konekt_courier.engine');

        try {
            $response = $engine->showHtmlLabel('sprinter', $awbNumber);
            $html = $response->getHtml();

            $response = new Response($html);

            return $response;
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }
}