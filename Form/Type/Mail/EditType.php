<?php

namespace Hexmedia\NewsletterBundle\Form\Type\Mail;

use Symfony\Component\Form\FormBuilderInterface;

class EditType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $this->addDeleteButton($builder);
    }
}