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
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

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
     * @param string $iconSet
     * @param string $shortcut
     */
    public function __construct(EntityManager $em, $iconSets, $shortcut = null)
    {
        $this->entityManager = $em;
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
    public function renderIcon(\Twig_Environment $env, $icon, $iconSet = null, $iconStyle = null, $inverted = false)
    {
        $template = $this->getIconTemplate($env);

        $context = array(
            'icon' => $icon,
            'inverted' => $inverted,
            'icon_style' => $iconStyle,
        );
        if (!$iconSet || !in_array($iconSet, $this->iconSets)) {
            $entity = $this->entityManager->getRepository('opwocoBootstrapBundle:BootstrapIcon')->findOneBy(array('identifier' => $icon));
            if (!$entity) {
                return $template->renderBlock($this->iconSets[IconSet::GLYPHICON], $context);
            }
            else {
                if ($entity->getGlyphicon()) {
                    return $template->renderBlock($this->iconSets[IconSet::GLYPHICON], $context);
                }
                else if ($entity->getFontawesome()) {
                    return $template->renderBlock($this->iconSets[IconSet::FONTAWESOME], $context);
                }
                else if ($entity->getFoundation()) {
                    return $template->renderBlock($this->iconSets[IconSet::FOUNDATION], $context);
                }
                else if ($entity->getIonicons()) {
                    return $template->renderBlock($this->iconSets[IconSet::IONICONS], $context);
                }
                else if ($entity->getOcticons()) {
                    return $template->renderBlock($this->iconSets[IconSet::OCTICONS], $context);
                }
                else if ($entity->getMaterial()) {
                    return $template->renderBlock($this->iconSets[IconSet::MATERIAL], $context);
                }
            }

        }
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
