<?php


namespace App\Helper;


use Symfony\Component\Form\FormInterface;

class FormErrors
{
    public static function getErrorMessages(FormInterface $form): array
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = (string) $child->getErrors(true, false);
            }
        }

        return $errors;
    }
}
