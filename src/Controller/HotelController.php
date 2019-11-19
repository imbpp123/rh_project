<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class HotelController extends AbstractController
{

    /**
     * This function creates filter by field array for repository
     *
     * @param Request $request
     * @return array
     */
    private function getHotelFilter(Request $request): array
    {
        $filter = [];
        if ($uuid = $request->get("uuid")) {
            $filter["uuid"] = $uuid;
        }
        return $filter;
    }

    /**
     * @param Request $request
     * @param HotelRepository $hotelRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getListAction(Request $request, HotelRepository $hotelRepository, SerializerInterface $serializer)
    {
        if ($filter = $this->getHotelFilter($request)) {
            $result = $hotelRepository->findBy($filter);
        } else {
            $result = $hotelRepository->findAll();
        }

        return new JsonResponse(
            $serializer->serialize($result, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param HotelRepository $hotelRepository
     * @param ReviewRepository $reviewRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getReviewListAction(
        Request $request,
        HotelRepository $hotelRepository,
        ReviewRepository $reviewRepository,
        SerializerInterface $serializer
    ) {
        $hotelId = $request->get('hotelId');

        if (null === $hotelRepository->find($hotelId)) {
            return new JsonResponse("Hotel not found", Response::HTTP_NOT_FOUND);
        }

        $reviews = $reviewRepository->findBy(['hotelId' => $hotelId]);
        return new JsonResponse(
            $serializer->serialize($reviews, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param HotelRepository $hotelRepository
     * @param ReviewRepository $reviewRepository
     * @return JsonResponse
     */
    public function getScoreAverageAction(
        Request $request,
        HotelRepository $hotelRepository,
        ReviewRepository $reviewRepository
    ) {
        $hotelId = $request->get('hotelId');

        if (null === $hotelRepository->find($hotelId)) {
            return new JsonResponse("Hotel not found", Response::HTTP_NOT_FOUND);
        }

        $response = new JsonResponse(
            $reviewRepository->getHotelAvgScore($hotelId),
            Response::HTTP_OK,
            [],
            true
        );
        $response->setMaxAge(3600);

        return $response;
    }
}
