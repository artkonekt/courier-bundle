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

use Konekt\Courier\Dpd\PackagePopulatorInterface;
use Konekt\Courier\Dpd\Transaction\CancelShipment\CancelShipmentRequest;
use Konekt\Courier\Dpd\Transaction\CreateShipment\CreateShipmentRequest;
use Konekt\Courier\Dpd\Transaction\FindSite\FindSiteRequest;
use Konekt\Courier\Dpd\Transaction\PrintAwb\PrintRequest;
use Konekt\CourierBundle\Event\AwbCreatedEvent;
use Konekt\CourierBundle\Event\AwbDeletedEvent;
use Konekt\CourierBundle\Event\CourierEvents;
use Konekt\CourierBundle\FormType\DpdPackageType;
use Konekt\Courier\Dpd\Package;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

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

        if ($populator = $this->getPackagePopulator()) {
            $populator->populate($packageId, $package);
        }

        $form = $this->createForm(new DpdPackageType(), $package, ['action' => $this->generateUrl('konekt_courier_dpd_create_awb', ['packageId' => $packageId])]);

        $response = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO: deal with possible exception occurring here
            $processor = $this->get('konekt_courier.dpd.request.processor');
            $createAwbRequest = new CreateShipmentRequest($package);
            $response = $processor->process($createAwbRequest);

            if ($response->isSuccess()) {
                $awbNumber = $response->getAwbNumber();

                $event = new AwbCreatedEvent($packageId, $awbNumber, 'dpd', json_encode($response->getBody()));
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);

                return $this->render('KonektCourierBundle:Dpd:success.html.twig', compact('awbNumber'));
            }
        }

        return $this->render('KonektCourierBundle:Dpd:form.html.twig', array(
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
    public function awbShowDetailsAction($awbNumber)
    {
        return $this->render('KonektCourierBundle:Dpd:details.html.twig', compact('awbNumber'));
    }

    /**
     * Deletes an AWB.
     *
     * @param $awbNumber
     *
     * @return Response
     * @throws Exception
     */
    public function awbDeleteAction($awbNumber)
    {
        try {
            $processor = $this->get('konekt_courier.dpd.request.processor');
            $awbRequest = new CancelShipmentRequest($awbNumber);
            $response = $processor->process($awbRequest);

            $error = false;
            if ($response->isSuccess()) {
                $event = new AwbDeletedEvent($awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_DELETED, $event);
            } else {
                $error = $response->getErrorMessage();
            }

            return $this->render('KonektCourierBundle:Dpd:delete_result.html.twig', compact('awbNumber', 'error'));
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    /**
     * Shows the AWB label in PDF format.
     *
     * @param $awbNumber
     *
     * @return Response
     * @throws Exception
     */
    public function awbShowPdfAction($awbNumber)
    {
        $parcelIds = $this->getParcelIds($awbNumber);

        try {

            $processor = $this->get('konekt_courier.dpd.request.processor');
            $awbRequest = new PrintRequest($parcelIds);
            $response = $processor->process($awbRequest);

            if ($response->isSuccess()) {
                $pdf = $response->getPdf();

                $response = new Response($pdf);
                $response->headers->set('Content-Type', 'application/pdf');

                return $response;
            } else {
                $error = $response->getErrorMessage();
                $response = new Response($error);

                return $response;
            }


        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    public function findSiteAction(Request $request)
    {
        $name = $request->query->get('name');
        $region = $request->query->get('region');

        try {

            $processor = $this->get('konekt_courier.dpd.request.processor');
            $request = new FindSiteRequest($name, $region);
            $response = $processor->process($request);

            if ($response->isSuccess()) {
                $response = new Response(json_encode($response->getSites()));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            } else {
                $error = $response->getErrorMessage();
                $response = new Response($error);

                return $response;
            }


        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    /**
     * Returns the parcel IDS belonging to a given shipment number.
     *
     * @param $awbNumber
     *
     * @return array
     * @throws Exception
     */
    private function getParcelIds($awbNumber)
    {
        $shipmentRepository = $this->get('sylius.repository.shipment');
        $shipment = $shipmentRepository->findOneBy(['tracking' => $awbNumber, 'courier' => 'dpd']);

        if (!$shipment) {
            throw new Exception(sprintf('Shipment with DPD tracking number %s not found', $awbNumber));
        }

        if ($courierData = json_decode($shipment->getCourierData())) {
            $parcelIds = array_map(function ($parcel) { return $parcel->id; }, $courierData->parcels);
        }

        return $parcelIds;
    }

    /**
     * Returns the package populator service provided by the client application. If no such service was provided, returns
     * null.
     *
     * @return PackagePopulatorInterface
     */
    private function getPackagePopulator()
    {
        $populator = null;

        if ($this->container->has('konekt_courier.dpd.package.populator')) {
            $populator = $this->get('konekt_courier.dpd.package.populator');
        }

        return $populator;
    }
}