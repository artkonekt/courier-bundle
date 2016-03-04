<?php
/**
 * Contains class FancourierTestController
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-02
 * @version     2016-03-02
 */

namespace Konekt\CourierBundle\Controller;

use Konekt\Courier\FanCourier\Package;
use Konekt\Courier\FanCourier\SingleAwbCreator;
use Konekt\Courier\FanCourier\Transaction\CreateAwb\CreateAwbRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FancourierTestController extends Controller
{
    public function testCreationAction()
    {
        $package = new Package();
        $package->tip = 'standard';

        $package->localitate = 'Târgu Mureș';
        $package->judet = 'Mureș';
        $package->strada = 'Aleea Carpati 12';
        $package->telefon = '0758099432';
        $package->nume_destinatar = 'Name 1';

        $package->plata_expeditii = 'expeditor';

        $package->greutate = 12;
        $package->nr_colet = 1;

        $package->observatii = 'Livrare ceva';

        $processor = $this->get('konekt_courier.fancourier.request.processor');
        $createAwbRequest = new CreateAwbRequest($package);
        $response = $processor->process($createAwbRequest);

        var_dump($response);
        return new Response();
    }
}