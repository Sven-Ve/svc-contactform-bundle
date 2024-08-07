<?php

namespace Svc\ContactformBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SvcContactformBundle extends AbstractBundle
{
  public function getPath(): string
  {
    return \dirname(__DIR__);
  }

  public function configure(DefinitionConfigurator $definition): void
  {
    $definition->rootNode()
      ->children()
        ->scalarNode('contact_mail')->cannotBeEmpty()->defaultValue('test@test.com')->info('Email adress for contact mails')->end()
        ->scalarNode('route_after_send')->cannotBeEmpty()->defaultValue('index')->info('Which route should by called after amil sent')->end()
        ->booleanNode('enable_captcha')->defaultFalse()->info('Enable captcha for contact form?')->end()
        ->booleanNode('enable_copy_to_me')->defaultTrue()->info('Enable sending a copy of the contact request to me too?')->end()
      ->end();
  }

  /**
   * @param array<mixed> $config
   */
  public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
  {
    $container->import('../config/services.yaml');

    $container->services()
      ->get('Svc\ContactformBundle\Controller\ContactController')
      ->arg(0, $config['enable_captcha'])
      ->arg(1, $config['contact_mail'])
      ->arg(2, $config['route_after_send'])
      ->arg(3, $config['enable_copy_to_me'])
    ;
  }
}
