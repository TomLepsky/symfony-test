<?php

namespace App\Helper;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorHelper
{
    public function transformErrors(ConstraintViolationListInterface $violationList) : array
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}