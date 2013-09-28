<?php

namespace Hexmedia\NewsletterBundle\Form\Type\SignInType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignInType extends AbstractType {

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "hexmedia_newsletter_sign_in";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Hexmedia\NewsletterBundle\Entity\Person'
        ]);
    }
}