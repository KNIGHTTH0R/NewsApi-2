<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\FormInterface;

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
            foreach ($errors as $error) {
                $er[] = $error->getMessage();
            }
        }

        return $er;
    }
}
