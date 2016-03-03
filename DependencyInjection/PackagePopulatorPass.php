<?php
/**
 * Contains class PackagePopulatorPass
 *
 * @package     Konekt\CourierBundle\DependencyInjection
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-03
 * @version     2016-03-03
 */

namespace Konekt\CourierBundle\DependencyInjection;

use ReflectionClass;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PackagePopulatorPass implements CompilerPassInterface
{
    /**
     * Validates the package populator service provided by the application at compile time.
     * It also sets an alias for it for easy retrieval.
     *
     * @param ContainerBuilder $container
     *
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('konekt_courier.fancourier.package.populator.service')) {
            $populatorServiceName = $container->getParameter('konekt_courier.fancourier.package.populator.service');

            if (!$container->has($populatorServiceName)) {
                throw new Exception("Package populator service '$populatorServiceName' does not exist");
            }

            $populatorServiceDefinition = $container->getDefinition($populatorServiceName);
            $class = new ReflectionClass($populatorServiceDefinition->getClass());
            if (!$class->implementsInterface('Konekt\Courier\FanCourier\PackagePopulatorInterface')) {
                throw new Exception("Package populator service '$populatorServiceName' should implement Konekt\\Courier\\FanCourier\\PackagePopulatorInterface");
            }

            $container->setAlias('konekt_courier.fancourier.package.populator', $populatorServiceName);
        }
    }
}