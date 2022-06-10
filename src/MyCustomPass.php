<?php

namespace App;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MyCustomPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /*
        $aliasBefore = $container->getAlias('App\Repository\RestaurantRepositoryInterface');
        $repoType = 'file';
        if ('file' == $repoType) {
            $container->setAlias('App\Repository\RestaurantRepositoryInterface', new Alias('App\Repository\InMemoryRestaurantRepository'));
        } else {
            $container->setAlias('App\Repository\RestaurantRepositoryInterface', new Alias('App\Repository\RestaurantRepository'));
        }

        $aliasAfter = $container->getAlias('App\Repository\RestaurantRepositoryInterface');
        var_dump($aliasBefore, $aliasAfter);
        */

        /*$definition = $container->getDefinition('knpu_lorem_ipsum.knpu_ipsum');
        $references = [];
        foreach ($container->findTaggedServiceIds('knpu_ipsum_word_provider') as $id => $tags) {
            $references[] = new Reference($id);
        }

        $definition->setArgument(0, $references);*/
    }
}