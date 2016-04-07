<?php
/**
 * Contains class ModelFactory
 *
 * @package     Konekt\CourierBundle\Services
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-04-05
 * @version     2016-04-05
 */

namespace Konekt\CourierBundle\Services;

use Konekt\Courier\Common\Exception\InvalidCourierException;
use Symfony\Component\DependencyInjection\Container;

class ModelFactory implements ModelFactoryInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create($carrierName, $data)
    {
        switch ($carrierName) {
            case 'fancourier':
                $factory = $this->container->get('konekt_courier.fancourier.package.factory');
                return $factory->create($data);
                break;
            case 'sprinter_ppp':
            case 'sprinter':
                $factory = $this->container->get('konekt_courier.sprinter.package.factory');
                return $factory->create($data);
                break;
            default:
                throw new InvalidCourierException("Courier $carrierName not supported");
        }

    }
}