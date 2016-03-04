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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FancourierTestController extends Controller
{
    public function testCreationAction()
    {
        $awb = new Package();
        $awb->tip = 'standard';

        $awb->localitate = 'Târgu Mureș';
        $awb->judet = 'Mureș';
        $awb->strada = 'Aleea Carpati 12';
        $awb->telefon = '0758099432';
        $awb->nume_destinatar = 'Name 1';

        $awb->plata_expeditii = 'expeditor';

        $awb->greutate = 12;
        $awb->nr_colet = 1;

        $awb->observatii = 'Livrare ceva';

        $awbCreator = new SingleAwbCreator($this->get('courier.fancourier.api.credentials'));
        $result = $awbCreator->create($awb);

        var_dump($result);
        return new Response();
    }
}