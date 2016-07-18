<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * Class FormErrorsTrait
 */
trait FormErrorsTrait
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function getFormErrors(FormInterface $form)
    {
        $er = [];
        $errors = $form->getErrors(true);
        if ($errors->count()) {
            /** @var FormError $error */
            foreach ($errors as $error) {
                $er[$error->getOrigin()->getName()] = $error->getMessage();
            }
        }

        return $er;
    }
}
