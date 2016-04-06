<?php
/**
 * Contains class Configuration
 *
 * @package     Konekt\CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-03-03
 * @version     2016-03-03
 */

namespace Konekt\CourierBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle's configuration definition.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('konekt_courier');

        $rootNode
            ->children()
                ->arrayNode('couriers')
                    ->children()
                        ->arrayNode('fancourier')
                            ->children()
                                ->arrayNode('api')
                                    ->children()
                                        ->scalarNode('username')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('user_pass')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end() //api
                            ->end()
                        ->end() //fancourier
                    ->end()
                ->end() //couriers
            ->end()
        ;

        return $treeBuilder;
    }
}