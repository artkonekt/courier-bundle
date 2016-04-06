<?php
/**
 * Contains class SprinterTestController
 *
 * @package     Konekt\CourierBundle\Controller
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-07
 * @version     2016-03-07
 */

namespace Konekt\CourierBundle\Tests\Controller;


use Konekt\Courier\Sprinter\PartnerToPudo\Transaction\RegisterParcel\RegisterParcelRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SprinterTestController extends Controller
{
    public function testRegisterParcelAction(Request $request)
    {
        $processor = $this->get('konekt_courier.sprinter.request.processor');
        $registerParcelRequest = new RegisterParcelRequest();
        $response = $processor->process($registerParcelRequest)->getResponse();

        var_dump($response);

        // process response
        print('ErrorCode: ' . $response->getRegisterParcelContainerResult()->ErrorCode . "\n");
        foreach($response->getRegisterParcelContainerResult()->ParcelResults as $parcelResult) {
            print("\tOriginalBarcode: " . $parcelResult->getOriginalBarCode() . "\n");
            print("\tNewBarCode: " . $parcelResult->getNewBarCode() . "\n");
            print("\tShipmentID: " . $parcelResult->getShipmentID() . "\n");
            print("\tErrorCode: " . $parcelResult->getErrorCode() . "\n");
            print("\n");
        }

        return new Response();
    }
}