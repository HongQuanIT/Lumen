<?php
use Illuminate\Validation\ValidationException;

if (! function_exists('Validate_Exception')) {
    function Validate_Exception($mess,$status = 422)
    {
        throw ValidationException::withMessages($mess)->status($status);
    }
}