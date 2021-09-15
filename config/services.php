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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return function (ContainerConfigurator $configurator) {
    $parameters = $configurator->parameters();

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()

    ;
    $services->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Tests,Kernel.php}')
    ;
    // Register every commands
    $services->load('App\\Command\\', '../src/Command/')->tag('console.command');
    $services->set(StaticJwtProvider::class)->arg('$jwt', '%app.mercure.jwt%');
    $services->set(EventSourceHttpClient::class);
    $services->set(Publisher::class)
        ->args([
            '%app.mercure.hub%',
            service(StaticJwtProvider::class),
            service(HttpClientInterface::class),
        ])
    ;
};
