<?php

namespace Svc\ContactformBundle\Tests;

require_once __DIR__ . '/Dummy/AppKernelDummy.php';

use App\Kernel as AppKernel;
use Svc\ContactformBundle\SvcContactformBundle;
use Svc\UtilBundle\SvcUtilBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Test kernel.
 */
class SvcContactformTestingKernel extends Kernel
{
  use MicroKernelTrait;

  private $builder;

  private $routes;

  private $extraBundles;

  /**
   * @param array             $routes  Routes to be added to the container e.g. ['name' => 'path']
   * @param BundleInterface[] $bundles Additional bundles to be registered e.g. [new Bundle()]
   */
  public function __construct(?ContainerBuilder $builder = null, array $routes = [], array $bundles = [])
  {
    $this->builder = $builder;
    $this->routes = $routes;
    $this->extraBundles = $bundles;

    parent::__construct('test', true);
  }

  public function registerBundles(): array
  {
    return [
      new SvcContactformBundle(),
      new FrameworkBundle(),
      new TwigBundle(),
      new SvcUtilBundle(),
    ];
  }

  public function registerContainerConfiguration(LoaderInterface $loader): void
  {
    if (null === $this->builder) {
      $this->builder = new ContainerBuilder();
    }

    $builder = $this->builder;

    $loader->load(function (ContainerBuilder $container) use ($builder) {
      $container->merge($builder);

      $container->loadFromExtension(
        'framework',
        [
          'secret' => 'foo',
          'http_method_override' => false,
          'router' => [
            'resource' => 'kernel::loadRoutes',
            'type' => 'service',
            'utf8' => true,
          ],
        ]
      );

      $container->register(AppKernel::class)
      ->setAutoconfigured(true)
      ->setAutowired(true);

      $container->register('kernel', static::class)->setPublic(true);

      $kernelDefinition = $container->getDefinition('kernel');
      $kernelDefinition->addTag('routing.route_loader');
    });
  }

  /**
   * load bundle routes.
   *
   * @return void
   */
  protected function configureRoutes(RoutingConfigurator $routes)
  {
    $routes->import(__DIR__ . '/../config/routes.yaml')->prefix('/contactform/{_locale}');
  }

  protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
  {
  }
}
