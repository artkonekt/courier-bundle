<?php
/**
 * Contains class CarrierDetailsFormFactoryInterface
 *
 * @package     Artkonekt\SyliusShippingBundle\Controller
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-01
 * @version     2016-04-01
 */

namespace Konekt\CourierBundle\Services;


interface CarrierDetailsFormTypeResolverInterface
{
    public function resolve($carrierName);
}