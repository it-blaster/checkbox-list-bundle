<?php

namespace ItBlaster\CheckboxListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Виджет "Список чекбоксов"
 *
 * Class CheckboxListType
 * @package ItBlaster\CheckboxListBundle\Form\Type
 */
class CheckboxListType extends AbstractType
{
    protected $choices = array();
    protected $name = 'checkbox_list';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * Дефолтные значения
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array(
            'filter_add_foreign_object',
            'foreign_objects',
            'foreign_object_model',
            'bundle_alias'
        ));

        $resolver->setDefaults(array(
            'required'  => false,
            'multiple'  => true,
            'expanded'  => true,
        ));
    }

    /**
     * Инициализация переменных для шаблона виджета
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['foreign_objects'] = $options['foreign_objects'];
        $view->vars['filter_add_foreign_object'] = $options['filter_add_foreign_object'];
        $view->vars['foreign_object_model'] = $options['foreign_object_model'];
        $bundle = $options['bundle_alias'];
        $foreign_alias = str_replace('_', '', $options['foreign_object_model']);
        $view->vars['foreign_object_link_add'] = 'admin_'.$bundle.'_'.$foreign_alias.'_create';
        $view->vars['foreign_object_link_edit'] = 'admin_'.$bundle.'_'.$foreign_alias.'_edit';
    }

    /**
     * Родительский виджет
     *
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * Имя виджета
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}