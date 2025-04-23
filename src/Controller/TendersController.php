<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TenderRepository;

class TendersController extends AbstractController
{
    #[Route('/tenders', name: 'app_tenders')]
    public function index(TenderRepository $tenderRepository): JsonResponse
    {
        $tenders = $tenderRepository->findTenders();
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
            'tenders' => $tendersData
        ]);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }
}
