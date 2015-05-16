<?php

/*
 * This file is part of the opwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\Twig;


/**
 * Reads Initializr configuration file and generates
 * corresponding Twig Globals.
 *
 * @author Paweł Madej (nysander) <pawel.madej@profarmaceuta.pl>
 */
class InitializrTwigExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'dns_prefetch'      => $this->parameters['dns_prefetch'],
            'meta'              => $this->parameters['meta'],
            'google'            => $this->parameters['google'],
            'diagnostic_mode'   => $this->parameters['diagnostic_mode'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'form_help' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'initializr';
    }
}
