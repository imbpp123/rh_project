<?php

namespace App\Controller;

use App\Repository\HotelChainRepository;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ChainController extends AbstractController
{
    /**
     * @param HotelChainRepository $chainRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getListAction(HotelChainRepository $chainRepository, SerializerInterface $serializer)
    {
        return new JsonResponse(
            $serializer->serialize($chainRepository->findAll(), 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param HotelChainRepository $chainRepository
     * @param HotelRepository $hotelRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getHotelListAction(
        Request $request,
        HotelChainRepository $chainRepository,
        HotelRepository $hotelRepository,
        SerializerInterface $serializer
    ) {
        $chainId = $request->get('chainId');

        if (null === $chainRepository->find($chainId)) {
            return new JsonResponse("Chain not found", Response::HTTP_NOT_FOUND);
        }

        $data = $hotelRepository->findBy(["chainId" => $chainId]);
        return new JsonResponse(
            $serializer->serialize($data, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
