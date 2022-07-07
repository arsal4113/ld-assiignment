<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Service\ContactService;
use App\Service\ErrorMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;
    private ContactService $contactService;
    private EntityManagerInterface $entityManager;
    private ErrorMessageHandler $errorMessageHandler;

    public function __construct(ContactRepository $contactRepository, EntityManagerInterface $entityManager, ErrorMessageHandler $errorMessageHandler, ContactService $contactService)
    {
        $this->contactRepository = $contactRepository;
        $this->contactService = $contactService;
        $this->entityManager = $entityManager;
        $this->errorMessageHandler = $errorMessageHandler;
    }

    #[Route('/contacts', name: 'get_contact', methods: 'GET')]
    public function index(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search_term');
            $per_page = (int)($request->get('per_page') ?? 15);
            $page = (int)($request->get('page') ?? 1);
            $contacts = [];

            if (null === $searchTerm || '' === trim($searchTerm)) {
                $contacts = $this->contactRepository->getAll($per_page, $page);
            }

            if ($searchTerm) {
                $name = trim($searchTerm);
                $contacts = $this->contactRepository->findByName($name, $per_page, $page);
            }

            return $this->json([
                'contacts' => $contacts,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $per_page,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/contacts', name: 'post_contact', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        try {
            $contactData = json_decode($request->getContent(), true) ?? [];

            $violations = $this->contactService->validate($contactData);
            if ($violations->count() > 0) {
                $errors = $this->errorMessageHandler->getValidationErrors($violations);

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $contactData['birthday'] = new \DateTime($contactData['birthday']);

            $contact = new Contact();
            $contact->setFirstName($contactData['firstName'])
                ->setLastName($contactData['lastName'])
                ->setAddress($contactData['address'])
                ->setPhoneNumber($contactData['phoneNumber'])
                ->setBirthday($contactData['birthday'])
                ->setPicture($contactData['picture'])
                ->setEmail($contactData['email']);

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $responseContact = $this->contactRepository->findById($contact->getId());

            return $this->json([
                'contact' => $responseContact,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $this->errorMessageHandler->exceptionError($e),
            ], Response::HTTP_CONFLICT);
        }
    }

    #[Route('/contacts/{contactId}', name: 'view_contact', methods: 'GET')]
    public function view(Request $request, string $contactId): JsonResponse
    {
        try {
            $contact = $this->contactRepository->findById($contactId);

            if (empty($contact)) {
                return $this->json([
                    'message' => 'Contact could not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            return $this->json([
                'contact' => $contact,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);
        }
    }

    #[Route('/contacts/{contactId}', name: 'patch_contact', methods: 'PATCH')]
    public function patch(Request $request, string $contactId): JsonResponse
    {
        try {
            $contact = $this->contactRepository->find(['id' => $contactId]);
            if (null === $contact) {
                return $this->json([
                    'message' => 'Contact could not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            $contactData = json_decode($request->getContent(), true) ?? [];

            $violations = $this->contactService->validate($contactData);
            if ($violations->count() > 0) {
                $errors = $this->errorMessageHandler->getValidationErrors($violations);

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $contactData['birthday'] = new \DateTime($contactData['birthday']);

            $contact->setFirstName($contactData['firstName'])
                ->setLastName($contactData['lastName'])
                ->setAddress($contactData['address'])
                ->setPhoneNumber($contactData['phoneNumber'])
                ->setBirthday($contactData['birthday'])
                ->setEmail($contactData['email']);

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $responseContact = $this->contactRepository->findById($contactId);

            return $this->json([
                'contact' => $responseContact,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $this->errorMessageHandler->exceptionError($e),
            ], Response::HTTP_CONFLICT);
        }
    }

    #[Route('/contacts/{contactId}', name: 'delete_contact', methods: 'DELETE')]
    public function delete(Request $request, string $contactId): JsonResponse
    {
        $contact = $this->contactRepository->find(['id' => $contactId]);
        if (null === $contact) {
            return $this->json([
                'message' => 'Contact could not found.',
            ], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
