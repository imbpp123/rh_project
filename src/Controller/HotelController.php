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
     * @param HotelRepository $hotelRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getListAction(HotelRepository $hotelRepository, SerializerInterface $serializer)
    {
        return new JsonResponse(
            $serializer->serialize($hotelRepository->findAll(), 'json'),
            Response::HTTP_OK
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
            Response::HTTP_OK
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

        return new JsonResponse(
            $reviewRepository->getHotelAvgScore($hotelId),
            Response::HTTP_OK
        );
    }
}
