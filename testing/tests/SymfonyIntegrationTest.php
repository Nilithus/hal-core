<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class SymfonyIntegrationTest extends PHPUnit_Framework_TestCase
{
    public function testContainerCompiles()
    {
        $configRoot = __DIR__ . '/../../configuration';

        $container = new ContainerBuilder;
        $builder = new YamlFileLoader($container, new FileLocator($configRoot));
        $builder->load('hal-core.yml');

        $container->compile();
    }
}
