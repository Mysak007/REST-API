<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Factory\ResponseFactory;
use App\Form\CreateUserType;
use App\Form\UserFilterType;
use App\Helper\FormErrors;
use App\Model\UserFilter;
use App\Repository\FollowerRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class ApiUserController extends AbstractController
{
    private ResponseFactory $responseFactory;
    private UserRepository $userRepository;
    private FollowerRepository $followerRepository;
    private UserService $userService;

    public function __construct(
        ResponseFactory $responseFactory,
        UserRepository  $userRepository,
        FollowerRepository $followerRepository,
        UserService     $userService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
        $this->userService = $userService;
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns user list with pagination and optional sorting by nick. Page starts with 1",
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     example="1",
     *     required=true,
     *     description="The field used for pagination",
     *     @OA\Schema(type="int")
     * )
     * @OA\Parameter(
     *     name="sortByColumn",
     *     in="query",
     *     example="nick",
     *     required=true,
     *     description="The field used for sorting by column name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="sortDirection",
     *     in="query",
     *     example="ASC",
     *     required=true,
     *     description="The field used for sorting direction (ASC,DESC)",
     *     @OA\Schema(type="string")
     * )
     */
    #[Route('/api/user/all', name: 'api_user_list', methods: ["GET"])]
    public function allUsers(Request $request): JsonResponse
    {
        $pagination = new UserFilter();

        $form = $this->createForm(UserFilterType::class, $pagination);
        $form->submit($request->query->all());

        if ($form->isValid()) {
            $paginatedUsers = $this->userRepository->filterUsers($pagination);

            return $this->responseFactory->createResponse($paginatedUsers, ['pagination','user_all']);
        }

        return $this->responseFactory->createNotOkResponse('Bad request', Response::HTTP_BAD_REQUEST, FormErrors::getErrorMessages($form));
    }

    #[OA\Response(properties: array('response' => '200', 'description' => "Returns user detail by id"))]
    #[Route('/api/user/{id}', name: 'api_user_detail', methods: ["GET"])]
    public function detailUser(User $user): JsonResponse
    {
        return $this->responseFactory->createResponse($user, ['user_detail']);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"create_user"}))
     *     )
     * )
     */
    #[Route('/api/user/create', name: 'api_user_create', methods: ["PUT"])]
    #[IsGranted("ROLE_ADMIN")]
    public function createUser(Request $request): JsonResponse
    {
        $user = new User();

        $form = $this->createForm(CreateUserType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            try {
                $this->userService->createUser($user);
            } catch (\Exception $exception) {
                return $this->responseFactory->createNotOkResponse($exception->getMessage());
            }

            return $this->responseFactory->createOkResponse();
        }
    }

    #[OA\Response(properties: array('response' => '200', 'description' => "Remove user"))]
    #[Route('/api/user/{id}/delete', name: 'api_user_delete', methods: ["DELETE"])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteUser(User $user): JsonResponse
    {
        try {
            $this->userRepository->remove($user, true);
        } catch (\Exception $exception) {
            return $this->responseFactory->createNotOkResponse($exception->getMessage());
        }

        return $this->responseFactory->createOkResponse();
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns followers list of user with pagination and sorting by nick. Page starts with 1"
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     example="1",
     *     required=true,
     *     description="The field used for pagination",
     *     @OA\Schema(type="int")
     * )
     * @OA\Parameter(
     *     name="sortByColumn",
     *     in="query",
     *     example="nick",
     *     required=true,
     *     description="The field used for sorting by column name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="sortDirection",
     *     in="query",
     *     example="ASC",
     *     required=true,
     *     description="The field used for sorting direction (ASC,DESC)",
     *     @OA\Schema(type="string")
     * )
     */
    #[Route('/api/user/{id}/follower-list', name: 'api_user_follow_list', methods: ["GET"])]
    public function followersList(User $user, Request $request): JsonResponse
    {
        $filter = new UserFilter();

        $form = $this->createForm(UserFilterType::class, $filter);
        $form->submit($request->query->all());

        if ($form->isValid()) {
            $filterResult = $this->followerRepository->findUserFollowers($user, $filter);

            return $this->responseFactory->createResponse($filterResult, ['pagination','follow_list']);
        }

        return $this->responseFactory->createNotOkResponse('Bad request', Response::HTTP_BAD_REQUEST, FormErrors::getErrorMessages($form));
    }

    #[OA\Response(properties: array('response' => '200', 'description' => "Follow another user (currently logged-in user can follow any other user)"))]
    #[Route('/api/user/{id}/follow', name: 'api_user_follow', methods: ["PATCH"])]
    public function followUser(User $user): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        try {
            $this->userService->followUser($user, $currentUser);
        } catch (\Exception $exception) {
            return $this->responseFactory->createNotOkResponse($exception->getMessage());
        }

        return $this->responseFactory->createOkResponse();
    }
}
