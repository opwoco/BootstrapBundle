<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class opwocoBootstrapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('bootstrap.yml');
        $loader->load('twig.yml');

        if (isset($config['bootstrap'])) {
            if (!isset($config['bootstrap']['install_path'])) {
                throw new \RuntimeException('Please specify the "bootstrap.install_path" or disable "opwoco_bootstrap" in your application config.');
            }
            $container->setParameter('opwoco_bootstrap.bootstrap.install_path', $config['bootstrap']['install_path']);
        }

        /**
         * Form
         */
        if (isset($config['form'])) {
            $loader->load('form.yml');
            foreach ($config['form'] as $key => $value) {
                if (is_array($value)) {
                    $this->remapParameters($container, 'opwoco_bootstrap.form.'.$key, $config['form'][$key]);
                } else {
                    $container->setParameter(
                        'opwoco_bootstrap.form.'.$key,
                        $value
                    );
                }
            }

            // Set tags
            $types = array(
                'opwoco_bootstrap.form.type.tab' => 'tab',
                'opwoco_bootstrap.form.type.form_actions' => 'form_actions',
            );
            foreach ($types as $type => $alias) {
                $typeDefinition = $container->getDefinition($type);
                $typeDefinition->addTag('form.type', method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                    ? array()
                    : array('alias' => $alias)
                );
            }
        }

        /**
         * Menu
         */
        if ($this->isConfigEnabled($container, $config['menu']) || $this->isConfigEnabled($container, $config['navbar'])) {
            $loader->load('menu.yml');
            $this->remapParameters($container, 'opwoco_bootstrap.menu', $config['menu']);

        }

        /**
         * Icons
         */
        if (isset($config['icons'])) {
            $this->remapParameters($container, 'opwoco_bootstrap.icons', $config['icons']);
        }

        /**
         * Initializr
         */
        if (isset($config['initializr'])) {
            $loader->load('initializr.yml');
            $this->remapParameters($container, 'opwoco_bootstrap.initializr', $config['initializr']);
        }

        /**
         * Flash
         */
        if (isset($config['flash'])) {
            $mapping = array();

            foreach ($config['flash']['mapping'] as $alertType => $flashTypes) {
                foreach ($flashTypes as $type) {
                    $mapping[$type] = $alertType;
                }
            }

            $container->getDefinition('opwoco_bootstrap.twig.extension.bootstrap_flash')
                ->replaceArgument(0, $mapping);

        }
    }

    /**
     * Remap parameters.
     *
     * @param ContainerBuilder $container
     * @param string           $prefix
     * @param array            $config
     */
    private function remapParameters(ContainerBuilder $container, $prefix, array $config)
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('%s.%s', $prefix, $key), $value);
        }
    }
}
