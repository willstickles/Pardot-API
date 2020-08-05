<?php

namespace CyberDuck\PardotApi\Validator;

class ArrayValidator extends Validator
{
    /**
     * Validation method
     *
     * @param mixed $value
     * @return boolean
     */
    public function validate($value): bool
    {
        return is_array($value);
    }
}