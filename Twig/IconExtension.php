<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\Twig;

use Doctrine\ORM\EntityManager;
use opwoco\Bundle\BootstrapBundle\Constant\IconSet;
use Symfony\Component\HttpFoundation\Response;

/**
 * OpwocoBootstrap Icon Extension.
 *
 */
class IconExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $iconSets;

    /**
     * @var \Twig_Template
     */
    protected $iconTemplate;

    /**
     * Constructor.
     *
     * @param array $iconSets
     * @param string $shortcut
     */
    public function __construct($iconSets, $shortcut = null)
    {
        $this->iconSets = $iconSets;
        $this->shortcut = $shortcut;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $options = array(
            'is_safe' => array('html'),
            'needs_environment' => true,
        );

        $functions = array(
            new \Twig_SimpleFunction('opwoco_bootstrap_icon', array($this, 'renderIcon'), $options),
        );

        if ($this->shortcut) {
            $functions[] = new \Twig_SimpleFunction($this->shortcut, array($this, 'renderIcon'), $options);
        }

        return $functions;
    }

    /**
     * Renders the icon.
     *
     * @param string  $icon
     * @param boolean $inverted
     *
     * @return Response
     */
    public function renderIcon(\Twig_Environment $env, $icon, $iconSet = 'glyphicon', $iconStyle = null, $inverted = false)
    {
        $template = $this->getIconTemplate($env);

        $context = array(
            'icon' => $icon,
            'inverted' => $inverted,
            'icon_style' => $iconStyle,
        );
        return $template->renderBlock($iconSet, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'opwoco_bootstrap_icon';
    }

    /**
     * @return \Twig_Template
     */
    protected function getIconTemplate(\Twig_Environment $env)
    {
        if ($this->iconTemplate === null) {
            $this->iconTemplate = $env->loadTemplate('@opwocoBootstrap/icons.html.twig');
        }
        return $this->iconTemplate;
    }
}
