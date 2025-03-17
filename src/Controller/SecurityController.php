<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        //TODO Find cleaner way to do this
        if($form->isSubmitted() && !$form->isValid()){
            foreach ($form->getErrors(true) as $error){
                $message = $translator->trans($error->getMessageTemplate());
                $this->addFlash('error', $message);
            }
            return $this->redirectToRoute('app_register');
        }

        if($form->isSubmitted() && $form->isValid()){
            $plainPassword = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($plainPassword !== $confirmPassword) {
                $message = $translator->trans('register.flash.password_mismatch');
                $this->addFlash('error', $message);
                return $this->redirectToRoute('app_register');
            }
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Generate confirmation token
            $token = Uuid::v4()->toRfc4122();
            $user->setConfirmationToken($token);
            $user->setIsVerified(false);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $confirmationUrl = $urlGenerator->generate(
                'app_confirm_email',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $email = (new Email())
                ->from('no-reply@grupa.rs')
                ->to($user->getEmail())
                ->subject($translator->trans('register.email_confirm.subject'))
                ->html($this->renderView('Email/confirmation.html.twig', [
                    'confirmationUrl' => $confirmationUrl,
                ]));

            $mailer->send($email);

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('security/registration.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        //TODO Check if this message is displayed and if it is change it to Serbian language
        if (!$user) {
            $this->addFlash('error', 'Invalid confirmation link.');
            return $this->redirectToRoute('app_login');
        }

        $user->setIsVerified(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error instanceof CustomUserMessageAccountStatusException) {
            $message = $translator->trans('login.flash.not_confirmed');
            $this->addFlash('error', $message);
        }else if($error && $error->getMessageKey() == 'Invalid credentials.'){
            $message = $translator->trans('login.flash.invalid_credentials');
            $this->addFlash('error', $message);
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
