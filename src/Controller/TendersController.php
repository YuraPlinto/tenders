<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TenderRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Tender;
use App\Dto\TenderDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class TendersController extends AbstractController
{
    #[Route('/tenders', name: 'app_tenders', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $name = null,
        #[MapQueryParameter] string $date = null,
        TenderRepository $tenderRepository
    ): JsonResponse
    {
        if (!\is_null($date)) {
            $dateTimeObj = new \DateTime($date);
        }
        $tenders = $tenderRepository->findTenders(name: $name, date: $dateTimeObj ?? null);
        $tendersData = [];
        foreach ($tenders as $tender) {
            $tendersData[] = [
            "Внешний код" => $tender->getCode(),
            "Номер"       => $tender->getNumber(),
            "Статус"      => !\is_null($tender->getStatus()) ? $tender->getStatus()->getName() : '',
            "Название"    => $tender->getName(),
            "Дата изм."   => $tender->getUpdatedAt()->format('Y-m-d H:i:s')
            ];
        }

        $response = new JsonResponse([
            'tendersQuantity' => \count($tendersData),
            'tenders' => $tendersData
        ]);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }

    #[Route('/tender/{number}', name: 'app_tender', methods: ['GET'])]
    public function show(
        string $number,
        TenderRepository $tenderRepository
    ): JsonResponse
    {
        $responseData = [];

        $tender = $tenderRepository->findOneBy(['number' => $number]);
        if (!$tender) {
            $responseData['error'] = "There isn't tender with number {$number}";
        } else {
            $tenderData = [
                "Внешний код" => $tender->getCode(),
                "Номер"       => $tender->getNumber(),
                "Статус"      => !\is_null($tender->getStatus()) ? $tender->getStatus()->getName() : '',
                "Название"    => $tender->getName(),
                "Дата изм."   => $tender->getUpdatedAt()->format('Y-m-d H:i:s')
            ];
            $responseData['tender'] = $tenderData;
        }

        $response = new JsonResponse($responseData);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }

    #[Route('/tenders/create', name: 'app_tender_create', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: Response::HTTP_NOT_FOUND
        )] TenderDto $tenderDto,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $errors = [];

        $status = null;
        if (!\is_null($tenderDto->status)) {
            $status = $statusRepository->findOneBy(['name' => $tenderDto->status]);
            if (\is_null($status)) {
                $errors[] = "There isn't status with name {$tenderDto->status}";
            }
        }

        $tender = new Tender();
        $tender->setName($tenderDto->name);
        $tender->setStatus($status);
        $tender->setCode($tenderDto->code);
        $tender->setNumber($tenderDto->number);
        $tender->setUpdatedAt(new \DateTime());

        $validationErrors = $validator->validate($tender);
        if (\count($validationErrors) > 0) {
            foreach ($validationErrors as $error) {
                $errors[] = $error->getMessage();
            }
        }

        if (\count($errors) == 0) {
            $entityManager->persist($tender);
            $entityManager->flush();
            $responseData['succes'] = true;
        } elseif (\count($errors) == 1) {
            $responseData['error'] = $errors[0];
        } else {
            $responseData['errors'] = $errors;
        }

        $response = new JsonResponse($responseData);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }
}
