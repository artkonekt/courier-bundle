<?php
/**
 * Contains class TypeResolver
 *
 * @package     AppBundle\Form\Type
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-01
 * @version     2016-04-01
 */

namespace Konekt\CourierBundle\Services;

use Konekt\Courier\Noop\Bridge\Symfony\Form\NoopDetailsType;
use Konekt\Courier\Sprinter\Bridge\Symfony\Form\SprinterDetailsType;

class CarrierDetailsFormTypeResolver implements CarrierDetailsFormTypeResolverInterface
{
    /**
     * @param $carrierName
     *
     * @return \AppBundle\Form\Type\NoopDetailsType|\AppBundle\Form\Type\SprinterDetailsType
     */
    public function resolve($carrierName)
    {
        switch ($carrierName) {
            case 'noop':
                $type = new NoopDetailsType();
                break;
            case 'sprinter_ppp':
                $type = new SprinterDetailsType();
                break;
            default:
                $type = new NoopDetailsType();
        }

        return $type;
    }
}