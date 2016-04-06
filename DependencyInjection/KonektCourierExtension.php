<?php
/**
 * Contains class KonektCourierExtension
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-01
 * @version     2016-03-01
 */

namespace Konekt\CourierBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Bundle's main extension.
 */
class KonektCourierExtension extends Extension implements PrependExtensionInterface
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

        $config = $config['couriers'];

        $container->setParameter('konekt_courier.fancourier.api.username', $config['fancourier']['api']['username']);
        $container->setParameter('konekt_courier.fancourier.api.user_pass', $config['fancourier']['api']['user_pass']);
        $container->setParameter('konekt_courier.fancourier.api.client_id', $config['fancourier']['api']['client_id']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = [
            'paths' => [
                "%kernel.root_dir%/../vendor/konekt/courier/FanCourier/Bridge/Symfony/Resources/views" => "KonektCourierFancourier",
                "%kernel.root_dir%/../vendor/konekt/courier/Sprinter/Bridge/Symfony/Resources/views" => "KonektCourierSprinter"
            ]
        ];
        $container->prependExtensionConfig('twig', $config);
    }
}