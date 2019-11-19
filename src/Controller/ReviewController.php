<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    /**
     * @param ReviewRepository $reviewRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getListAction(ReviewRepository $reviewRepository, SerializerInterface $serializer)
    {
        return new JsonResponse(
            $serializer->serialize($reviewRepository->findAll(), 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
