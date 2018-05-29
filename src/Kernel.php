<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use App\Application\DependencyInjection\RedisExtension;
use App\Infra\GCP\DependencyInjection\GCPExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class Kernel
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/log';
    }

    /**
     * @return \Generator|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        $contents = require dirname(__DIR__).'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    /**
     * @param ContainerBuilder  $container
     * @param LoaderInterface   $loader
     *
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $confDir = dirname(__DIR__).'/config';

        $container->registerExtension(new GCPExtension());
        $container->registerExtension(new RedisExtension());

        $loader->load($confDir.'/packages/*'.self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir.'/packages/'.$this->environment)) {
            $loader->load($confDir.'/packages/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        }
        $loader->load($confDir.'/services'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/services_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    /**
     * @param RouteCollectionBuilder  $routes
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = dirname(__DIR__).'/config';
        if (is_dir($confDir.'/routes/')) {
            $routes->import($confDir.'/routes/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        if (is_dir($confDir.'/routes/'.$this->environment)) {
            $routes->import($confDir.'/routes/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        $routes->import($confDir.'/routes'.self::CONFIG_EXTS, '/', 'glob');
    }
}
