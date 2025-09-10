<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\WorkRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private WorkRequestRepository $workRequests,
    ) { }

    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $userId = (string)$session->get('user_id');
        if ($userId === '') {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->userRepository->find($userId);
        if (!$user) {
            $session->remove('user_id');
            return $this->redirectToRoute('app_login');
        }

        $phone = $user->getPhone();
        $phoneStr = $phone !== null ? (string)$phone : '';
        $workRequests = $phoneStr !== ''
            ? $this->workRequests->findBy(['phone' => $phoneStr])
            : [];

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'workRequests' => $workRequests,
        ]);
    }
}
