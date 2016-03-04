<?php
/**
 * Contains class FancourierController
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-02-25
 * @version     2016-02-25
 */

namespace Konekt\CourierBundle\Controller;

use Konekt\Courier\FanCourier\Package;
use Konekt\Courier\FanCourier\PackagePopulatorInterface;
use Konekt\Courier\FanCourier\Transaction\AwbHtml\AwbHtmlRequest;
use Konekt\Courier\FanCourier\Transaction\AwbPdf\AwbPdfRequest;
use Konekt\Courier\FanCourier\Transaction\CreateAwb\CreateAwbRequest;
use Konekt\Courier\FanCourier\AwbHtml;
use Konekt\Courier\FanCourier\AwbPdf;
use Konekt\Courier\FanCourier\SingleAwbCreator;
use Konekt\CourierBundle\Event\AwbCreatedEvent;
use Konekt\CourierBundle\Event\CourierEvents;
use Konekt\CourierBundle\FormType\FancourierPackageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller with actions related to the Fancourier package carrier.
 */
class FancourierController extends Controller
{
    /**
     * Shows the AWB creation form and also processes the submitted form: creates an AWB in the FanCourier system.
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

        $form = $this->createForm(new FancourierPackageType(), $package, ['action' => $this->generateUrl('konekt_courier_fancourier_create_awb', ['packageId' => $packageId])]);

        $form->handleRequest($request);

        $response = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $processor = $this->get('konekt_courier.fancourier.request.processor');
            $createAwbRequest = new CreateAwbRequest($package);
            $response = $processor->process($createAwbRequest);

            if ($response->isSuccess()) {
                $awbNumber = $response->getAwbNumber();

                $event = new AwbCreatedEvent($packageId, $awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);

                return $this->render('KonektCourierBundle:Fancourier:success.html.twig', compact('awbNumber'));
            }
        }

        return $this->render('KonektCourierBundle:Fancourier:form.html.twig', array(
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
        return $this->render('KonektCourierBundle:Fancourier:details.html.twig', compact('awbNumber'));
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
        try {

            $processor = $this->get('konekt_courier.fancourier.request.processor');
            $awbRequest = new AwbPdfRequest($awbNumber);
            $response = $processor->process($awbRequest);
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

    /**
     * Shows the AWB label in HTML format.
     *
     * @param $awbNumber
     *
     * @return Response
     * @throws Exception
     */
    public function awbShowHtmlAction($awbNumber)
    {
        try {
            $processor = $this->get('konekt_courier.fancourier.request.processor');
            $awbRequest = new AwbHtmlRequest($awbNumber);
            $response = $processor->process($awbRequest);

            $html = $response->getHtml();

            $response = new Response($html);

            return $response;
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
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

        if ($this->container->has('konekt_courier.fancourier.package.populator')) {
            $populator = $this->get('konekt_courier.fancourier.package.populator');
        }

        return $populator;
    }
}