<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET','POST'])]
    public function login(Request $request, UserRepository $users, LoggerInterface $logger): Response
    {
        if ($request->isMethod('POST')) {
            $phone = preg_replace('/\D+/', '', (string) $request->request->get('phone'));
            if (!$phone) {
                $this->addFlash('error', 'Введите номер телефона');
                return $this->redirectToRoute('app_login');
            }

            $user = $users->findOneBy(['phone' => (int)$phone]);
            if (!$user) {
                $this->addFlash('error', 'Пользователь с таким телефоном не найден');
                return $this->redirectToRoute('app_login');
            }

            $code = random_int(1000, 9999);
            $session = $request->getSession();
            $session->set('sms_login', [
                'phone' => $phone,
                'code' => (string)$code,
                'expires_at' => time() + 300,
                'user_id' => $user->getStringifyId(),
            ]);

            $logger->info('SMS code for login', ['phone' => $phone, 'code' => $code]);
            return $this->render('auth/verify.html.twig', [
                'phone' => $phone,
                'dev_code' => $code, // show on page for demo
            ]);
        }

        return $this->render('auth/login.html.twig');
    }

    #[Route('/login/verify', name: 'app_login_verify', methods: ['POST'])]
    public function verify(Request $request): Response
    {
        $session = $request->getSession();
        $state = $session->get('sms_login');
        if (!$state) {
            $this->addFlash('error', 'Сессия авторизации не найдена');
            return $this->redirectToRoute('app_login');
        }
        $code = (string) $request->request->get('code');
        if (!$code || (string)$state['code'] !== trim($code)) {
            $this->addFlash('error', 'Неверный код');
            return $this->redirectToRoute('app_login');
        }
        if (time() > (int)$state['expires_at']) {
            $this->addFlash('error', 'Код истек');
            return $this->redirectToRoute('app_login');
        }
        $session->set('user_id', (string)$state['user_id']);
        $session->remove('sms_login');
        return $this->redirectToRoute('app_user');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();
        return $this->redirectToRoute('homepage');
    }
}
