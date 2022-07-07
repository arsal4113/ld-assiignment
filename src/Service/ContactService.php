<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $contactData): ConstraintViolationListInterface
    {
        $constraints = new Collection([
            'email' => [
                new Email(),
                new NotBlank(),
            ],
            'firstName' => [
                new Length(['min' => 1]),
                new NotBlank(),
            ],
            'lastName' => [
                new Length(['min' => 1]),
                new NotBlank(),
            ],
            'address' => [
                new Length(['min' => 1]),
                new NotBlank(),
            ],
            'phoneNumber' => [
                new Length(['min' => 1]),
                new NotBlank(),
            ],
            'birthday' => [
                new NotBlank(),
            ],
            'picture' => [
                new NotBlank(['allowNull' => true]),
            ],
        ]);

        return $this->validator->validate($contactData, $constraints);
    }
}

