<?php
/**
 * Contains class CourierBundle
 *
 * @package     CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas
 * @license     MIT
 * @since       2016-02-25
 * @version     2016-02-25
 */

namespace Konekt\CourierBundle;

use Konekt\CourierBundle\DependencyInjection\PackagePopulatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class KonektCourierBundle
 */
class KonektCourierBundle extends Bundle
{
    /**
     * @inheritdoc
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}