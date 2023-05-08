<?php

namespace App\Factory;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createResponse($data, array $groups): JsonResponse
    {
        $serialized = $this->serializer->serialize(
            $data,
            'json',
            SerializationContext::create()->setGroups($groups)
        );

        return new JsonResponse($serialized, 200, [], true);
    }

    public function createOkResponse(): JsonResponse
    {
        return new JsonResponse(['ok' => true]);
    }

    public function createNotOkResponse(string $message, int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR, array $errors = null): JsonResponse
    {
        $response = [
            "ok" => false,
            "message" => $message
        ];

        if($errors) {
            $response["errors"] = $errors;
        }

        return new JsonResponse($response, $httpCode);
    }
}
