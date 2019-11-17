<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Old controller that is not SOLID
 * It can do everything
 * I just refactored it. It works.
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/api/average", name="average", methods={"GET"})
     */
    public function getAverage(Request $request, ReviewRepository $reviewRepository)
    {
        $hotelId = $request->get('hotelId');

        if ($hotelId === null) {
            return new Response('Hotel not found.', Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($reviewRepository->getHotelAvgScore($hotelId)['score']);
    }

    /**
     * @Route("/api/reviews", name="review_list", methods={"GET"})
     * @param Request $request
     * @param ReviewRepository $reviewRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getReviews(Request $request, ReviewRepository $reviewRepository, SerializerInterface $serializer)
    {
        $hotelId = $request->get('hotelId');

        if (null === $hotelId) {
            $reviews = $reviewRepository->findAll();
        } else {
            $reviews = $reviewRepository->findBy(['hotelId' => $hotelId]);
        }

        return new JsonResponse(
            $serializer->serialize($reviews, 'json'),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/api/hotels", name="hotel_list", methods={"GET"})
     * @param HotelRepository $hotelRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getHotels(HotelRepository $hotelRepository, SerializerInterface $serializer)
    {
        return new JsonResponse(
            $serializer->serialize($hotelRepository->findAll(), 'json'),
            Response::HTTP_OK
        );
    }
}
