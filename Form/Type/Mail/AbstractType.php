<?php

namespace Hexmedia\NewsletterBundle\Form\Type\Mail;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Hexmedia\AdministratorBundle\Form\Type\CrudType;

class AbstractType extends CrudType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('content', 'raptor');

        $this->addButtons($builder);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hexmedia\NewsletterBundle\Entity\Mail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hexmedia_newsletterbundle_mailtype';
    }
}
