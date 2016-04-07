<?php
/**
 * Contains class Engine
 *
 * @package     Konekt\CourierBundle\Services
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-05
 * @version     2016-04-05
 */

namespace Konekt\CourierBundle\Services;


use Konekt\Courier\FanCourier\Transaction\AwbToHtml\AwbToHtmlRequest;
use Konekt\Courier\FanCourier\Transaction\AwbToPdf\AwbToPdfRequest;
use Konekt\Courier\FanCourier\Transaction\CreateAwb\CreateAwbRequest;
use Konekt\Courier\FanCourier\Transaction\DeleteAwb\DeleteAwbRequest;
use Konekt\Courier\Sprinter\PartnerToPudo\Transaction\AwbToHtml\AwbToHtmlRequest as SprinterAwbToHtmlRequest;
use Konekt\Courier\Sprinter\PartnerToPudo\Transaction\RegisterParcel\RegisterParcelRequest;
use Symfony\Component\DependencyInjection\Container;

class Engine
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createAwb($carrierName, $model)
    {
        switch ($carrierName) {
            case 'fancourier':
                $createAwbRequest = new CreateAwbRequest($model);
                $processor = $this->container->get('konekt_courier.fancourier.request.processor');
                $response = $processor->process($createAwbRequest);
                break;
            case 'sprinter_ppp':
                $createAwbRequest = new RegisterParcelRequest($model, RegisterParcelRequest::TYPE_PPP);
                $processor = $this->container->get('konekt_courier.sprinter.request.processor');
                $response = $processor->process($createAwbRequest);
                break;
            case 'sprinter':
                $createAwbRequest = new RegisterParcelRequest($model, RegisterParcelRequest::TYPE_HOMEDELIVERY);
                $processor = $this->container->get('konekt_courier.sprinter.request.processor');
                $response = $processor->process($createAwbRequest);
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }


        return $response;
    }

    public function deleteAwb($carrierName, $awbNumber)
    {
        switch ($carrierName) {
            case 'fancourier':
                $processor = $this->container->get('konekt_courier.fancourier.request.processor');
                $awbRequest = new DeleteAwbRequest($awbNumber);
                $response = $processor->process($awbRequest);
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }


        return $response;
    }

    public function showPdfLabel($carrierName, $awbNumber)
    {
        switch ($carrierName) {
            case 'fancourier':
                $processor = $this->container->get('konekt_courier.fancourier.request.processor');
                $awbRequest = new AwbToPdfRequest($awbNumber);
                $response = $processor->process($awbRequest);
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }

        return $response;
    }

    public function showHtmlLabel($carrierName, $awbNumber)
    {
        switch ($carrierName) {
            case 'fancourier':
                $processor = $this->container->get('konekt_courier.fancourier.request.processor');
                $awbRequest = new AwbToHtmlRequest($awbNumber);
                $response = $processor->process($awbRequest);
                break;
            case 'sprinter':
            case 'sprinter_ppp':
                $processor = $this->container->get('konekt_courier.sprinter.request.processor');
                $awbRequest = new SprinterAwbToHtmlRequest($awbNumber);
                $response = $processor->process($awbRequest);
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }

        return $response;
    }
}