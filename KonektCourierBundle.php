<?php

namespace Konekt\CourierBundle;

use Konekt\CourierBundle\DependencyInjection\PackagePopulatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Contains class CourierBundle
 *
 * @package     CourierBundle
 * @copyright   Copyright (c) 2016 Storm Storez Srl-D
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2016-02-25
 * @version     2016-02-25
 */
class KonektCourierBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PackagePopulatorPass());
    }

}