<?php

namespace Hexmedia\NewsletterBundle\Form\Type\Person;

use Symfony\Component\Form\FormBuilderInterface;

class AddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->addAddNextButton($builder);
    }
}