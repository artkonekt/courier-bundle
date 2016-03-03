<?php

namespace Konekt\CourierBundle\Controller;

use Konekt\Courier\FanCourier\Package;
use Konekt\Courier\FanCourier\PackagePopulatorInterface;
use Konekt\CourierBundle\Event\AwbCreatedEvent;
use Konekt\CourierBundle\Event\CourierEvents;
use Konekt\CourierBundle\FormType\FancourierPackageType;
use Konekt\Courier\FanCourier\AwbHtml;
use Konekt\Courier\FanCourier\AwbPdf;
use Konekt\Courier\FanCourier\SingleAwbCreator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contains class AwbController
 *
 * @package     CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-02-25
 * @version     2016-02-25
 */
class FancourierController extends Controller
{
    public function createAwbAction(Request $request, $packageId)
    {
        $package = new Package();

        if ($populator = $this->getPackagePopulator()) {
            $populator->populate($packageId, $package);
        }

        $form = $this->createForm(new FancourierPackageType(), $package, ['action' => $this->generateUrl('konekt_courier_fancourier_create_awb', ['packageId' => $packageId])]);

        $form->handleRequest($request);

        $result = null;

        //refactor this monstruosity
        if ($form->isSubmitted() && $form->isValid()) {

            $awbCreator = new SingleAwbCreator($this->get('konekt_courier.fancourier.api.credentials'));
            $result = $awbCreator->create($package);

            if ($result->isSuccess()) {
                $awbNumber = $result->getAwbNumber();

                $event = new AwbCreatedEvent($packageId, $awbNumber);
                $eventDispatcher = $this->container->get('event_dispatcher');
                $eventDispatcher->dispatch(CourierEvents::AWB_CREATED, $event);

                return $this->render('KonektCourierBundle:Fancourier:success.html.twig', compact('awbNumber'));
            }
        }

        return $this->render('KonektCourierBundle:Fancourier:form.html.twig', array(
            'form' => $form->createView(),
            'result' => $result
        ));
    }

    public function awbShowDetailsAction($awbNumber)
    {
        return $this->render('KonektCourierBundle:Fancourier:details.html.twig', compact('awbNumber'));
    }

    public function awbShowPdfAction($awbNumber)
    {
        try {
            $awbPdf = new AwbPdf($this->get('konekt_courier.fancourier.api.credentials'));

            $pdf = $awbPdf->getPdf($awbNumber);

            //Do not print alongside HTML result (will fail to load PDF)
            $response = new Response($pdf);
            $response->headers->set('Content-Type', 'application/pdf');

            return $response;
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    public function awbShowHtmlAction($awbNumber)
    {
        try {
            $awbHtml= new AwbHtml($this->get('konekt_courier.fancourier.api.credentials'));
            $html = $awbHtml->getHtml($awbNumber);

            $response = new Response($html);

            return $response;
        } catch (Exception $exc) {
            //TOREVIEW
            throw $exc;
        }
    }

    /**
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