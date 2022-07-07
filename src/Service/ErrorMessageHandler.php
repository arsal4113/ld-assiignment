<?php

namespace App\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ErrorMessageHandler
{
    public function getValidationErrors($violations = null): bool|array
    {
        if (empty($violations)) {
            return false;
        }
        $errors = [];
        foreach ($violations as $key => $violation) {
            $errorKey = str_replace(['[', ']'], '', $violation->getPropertyPath());
            if (!empty($errorKey)) {
                $errors[$errorKey][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    /**
     * Return exception message.
     */
    public function exceptionError(Exception $e): string
    {
        if ($e instanceof UniqueConstraintViolationException) {
            return 'A contact with the same email address already exists.';
        }

        if ($e instanceof FileException) {
            return 'An error occurred during file upload.';
        }

        return $e->getMessage();
    }
}

