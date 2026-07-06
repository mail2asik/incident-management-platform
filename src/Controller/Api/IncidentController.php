<?php

namespace App\Controller\Api;

use App\DTO\CreateIncidentDto;
use App\Entity\Incident;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/incidents')]
class IncidentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IncidentRepository $incidentRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'api_incident_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Fetch all incidents via repository
        $incidents = $this->incidentRepository->findAll();
        
        return $this->json($incidents, Response::HTTP_OK, [], ['groups' => 'incident:read']);
    }

    #[Route('', name: 'api_incident_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Deserialize JSON payload directly into our DTO
        /** @var CreateIncidentDto $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CreateIncidentDto::class, 'json');

        // Validate incoming payload requirements
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        // Initialize and persist our Domain entity 
        $incident = new Incident();
        $incident->setTitle($dto->title);
        $incident->setDescription($dto->description);
        $incident->setPriority($dto->priority);
        $incident->setStatus('open'); 

        $this->entityManager->persist($incident);
        $this->entityManager->flush();

        return $this->json($incident, Response::HTTP_CREATED);
    }
}