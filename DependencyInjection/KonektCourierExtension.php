<?php
/**
 * Contains class CourierExtension
 *
 * @package     Konekt\CourierBundle\DependencyInjection
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-03-01
 * @version     2016-03-01
 */

namespace Konekt\CourierBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KonektCourierExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('konekt_courier.fancourier.api.username', $config['fancourier']['api']['username']);
        $container->setParameter('konekt_courier.fancourier.api.user_pass', $config['fancourier']['api']['user_pass']);
        $container->setParameter('konekt_courier.fancourier.api.client_id', $config['fancourier']['api']['client_id']);

        if (isset($config['fancourier']['package_populator_service'])) {
            $container->setParameter('konekt_courier.fancourier.package.populator.service', $config['fancourier']['package_populator_service']);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.yml');
    }
}