<?php

/*
 * This file is part of the Bizmuth Bot project
 *
 * (c) Lemay Marc <flugv1@gmail.com>
 *     Twitch channel : https://twitch.tv/flugv1
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @experimental
 */

namespace App;

use Bismuth\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        if (is_file($path = \dirname(__DIR__).'/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
        parent::configureContainer($container);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        if (is_file($path = \dirname(__DIR__).'/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
