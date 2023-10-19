<?php

namespace App\dto;

use Symfony\Component\Form\DataTransformerInterface;

class RoleToArrayTransformer implements DataTransformerInterface
{
    public function transform($rolesArray)
    {
        // transform the array to a string
        return $rolesArray[0] ?? null;
    }

    public function reverseTransform($roleString)
    {
        // transform the string back to an array
        return [$roleString];
    }
}