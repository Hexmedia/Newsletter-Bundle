<?php

namespace Hexmedia\NewsletterBundle\Form\Type\Person;

use Hexmedia\AdministratorBundle\Form\Type\CrudType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstractType extends CrudType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', [
                'label' => 'Email'
            ])
            ->add('name', 'text', [
                'label' => 'Name'
            ]);

        $this->addButtons($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hexmedia\NewsletterBundle\Entity\Person'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hexmedia_newsletterbundle_personstype';
    }
}
