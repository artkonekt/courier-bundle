<?php

/**
 * Contains the DpdController class
 *
 * @package     Controller
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2018-07-04
 */


namespace Konekt\CourierBundle\Controller;

use Konekt\Courier\Dpd\Transaction\CreateShipment\CreateShipmentRequest;
use Konekt\CourierBundle\Event\AwbCreatedEvent;
use Konekt\CourierBundle\Event\CourierEvents;
use Konekt\CourierBundle\FormType\DpdPackageType;
use Konekt\Courier\Dpd\Package;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DpdController extends Controller
{
    /**
     * Shows the AWB creation form and also processes the submitted form: creates an AWB in the Dpd system.
     *
     * @param Request                                   $request
     * @param                                           $packageId The third party identifier of the package
     *
     * @return Response
     */
    public function createAwbAction(Request $request, $packageId)
    {
        $package = new Package();

        // TODO: populate package with real data

        $form = $this->createForm(new DpdPackageType(), $package, ['action' => $this->generateUrl('konekt_courier_dpd_create_awb', ['packageId' => $packageId])]);

        $response = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $processor = $this->get('konekt_courier.dpd.request.processor');
            $createAwbRequest = new CreateShipmentRequest($package);
            $response = $processor->process($createAwbRequest);

            if ($response->isSuccess()) {
                $awbNumber = $response->getAwbNumber();

                $event = new AwbCreatedEvent($packageId, $awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);

                return $this->render('KonektCourierBundle:Fancourier:success.html.twig', compact('awbNumber'));
            }
        }

        return $this->render('KonektCourierBundle:Dpd:form.html.twig', array(
            'form' => $form->createView(),
            'result' => $response
        ));


//
//        if ($populator = $this->getPackagePopulator()) {
//            $populator->populate($packageId, $package);
//        }
//
//        $form = $this->createForm(new FancourierPackageType(), $package, ['action' => $this->generateUrl('konekt_courier_fancourier_create_awb', ['packageId' => $packageId])]);
//
//        $form->handleRequest($request);
//
//        if (isset($populator) && $package->optiuni) {
//            $populator->addItems($packageId, $package);
//        }
//
//        $response = null;
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $processor = $this->get('konekt_courier.fancourier.request.processor');
//            $createAwbRequest = new CreateAwbRequest($package);
//            $response = $processor->process($createAwbRequest);
//
//            if ($response->isSuccess()) {
//                $awbNumber = $response->getAwbNumber();
//
//                $event = new AwbCreatedEvent($packageId, $awbNumber);
//                $eventDispatcher = $this->container->get('event_dispatcher');
//                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);
//
//                return $this->render('KonektCourierBundle:Fancourier:success.html.twig', compact('awbNumber'));
//            }
//        }
//
//        return $this->render('KonektCourierBundle:Fancourier:form.html.twig', array(
//            'form' => $form->createView(),
//            'result' => $response
//        ));

        //return new Response('Szevasztok tavasztok');
    }

}