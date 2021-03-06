<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use RuntimeException;
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DI
{
    const PRIMARY_CONFIGURATION_FILE = 'config/config.yaml';
    const ENV_CACHE_DISABLED = 'HAL_DI_DISABLE_CACHE_ON';

    // When the container is built (dev-mode), this dumps and loads the cached
    // container instead of using "ContainerBuilder"
    const BUILD_AND_CACHE = false;

    const DI_EXTENSIONS = [];

    /**
     * @param string $root
     * @param bool $resolveEnvironment
     *
     * @return ContainerBuilder
     */
    public static function buildDI(string $root, bool $resolveEnvironment = false)
    {
        $root = rtrim($root, '/');

        $container = new ContainerBuilder;
        $loader = new YamlFileLoader($container, new FileLocator($root));

        $extensions = [];
        foreach (static::DI_EXTENSIONS as $extClass) {
            if (!class_exists($extClass)) {
                throw new RuntimeException("Symfony DI Extension not found: \"${extClass}\"");
            }

            $extensions[] = new $extClass;
        }

        foreach ($extensions as $ext) {
            $container->registerExtension($ext);
        }

        $loader->load(static::PRIMARY_CONFIGURATION_FILE);

        foreach ($extensions as $ext) {
            $container->loadFromExtension($ext->getAlias());
        }

        $container->compile($resolveEnvironment);

        return $container;
    }

    /**
     * @param string $root
     * @param array $options
     *
     * @return ContainerInterface|bool
     */
    public static function getDI(string $root, array $options)
    {
        $class = $options['class'] ?? '';
        if (!$class) {
            return false;
        }

        $cacheDisabled = getenv(static::ENV_CACHE_DISABLED);
        if ($cacheDisabled) {
            return self::buildContainer($root, $class, $options);
        }

        return self::getCachedContainer($class);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $options
     *
     * @return string|bool
     */
    public static function cacheDI(ContainerBuilder $container, array $options)
    {
        $class = $options['class'] ?? '';
        if (!$class) {
            return false;
        }

        $exploded = explode('\\', $class);
        $config = array_merge($options, [
            'class' => array_pop($exploded),
            'namespace' => implode('\\', $exploded)
        ]);

        $dumper = self::buildDumper($container);
        return $dumper->dump($config);
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return PhpDumper
     */
    private static function buildDumper(ContainerBuilder $container)
    {
        $dumper = new PhpDumper($container);
        if (class_exists(PhpDumper::class)) {
            $dumper->setProxyDumper(new ProxyDumper);
        }

        return $dumper;
    }

    /**
     * @param string $class
     *
     * @return ContainerInterface
     */
    private static function getCachedContainer($class)
    {
        if (!class_exists($class)) {
            throw new RuntimeException("DI Cached Container class not found: \"${class}\"");
        }

        return new $class;
    }

    /**
     * @param string $root
     * @param string $class
     * @param array $options
     *
     * @return ContainerInterface
     */
    private static function buildContainer($root, $class, $options)
    {
        $container = static::buildDI($root, !static::BUILD_AND_CACHE);

        if (static::BUILD_AND_CACHE) {
            $cached = static::cacheDI($container, $options);

            $content = str_replace('<?php', '', $cached);
            eval($content);
            $container = new $class;
        }

        return $container;
    }
}
