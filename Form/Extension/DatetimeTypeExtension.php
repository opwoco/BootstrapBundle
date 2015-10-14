<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
* For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Extension for Datetime type.
 *
 */
class DatetimeTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ('single_text' === $options['widget']) {
            if (isset($options['datetimepicker'])) {
                $view->vars['datetimepicker'] = $options['datetimepicker'];
            }
            if (isset($options['widget_reset_icon'])) {
                $view->vars['widget_reset_icon'] = $options['widget_reset_icon'];
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Remove it when bumping requirements to SF 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if (method_exists($resolver, 'setDefined')) {
            $resolver->setDefined(array(
                'datetimepicker',
                'widget_reset_icon',
            ));
        } else { // Symfony <2.6 BC
            $resolver->setOptional(array(
                'datetimepicker',
                'widget_reset_icon',
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'datetime';
    }
}
