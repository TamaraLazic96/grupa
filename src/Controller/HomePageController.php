<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PageRepository $pageRepository): Response {

        $page = $pageRepository->findPageById(1);

        if (!$page) {
            throw new NotFoundHttpException('');
        }

        return $this->render('HomePage/index.html.twig', [
            'page' => $page
        ]);
    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs(PageRepository $pageRepository): Response {

        $page = $pageRepository->findPageById(2);

        if (!$page) {
            throw new NotFoundHttpException('');
        }

        return $this->render('HomePage/about-us.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, ParameterBagInterface $params, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $adminMail = $params->get('MAILER_ADMIN_EMAIL');

            $email = (new Email())
                ->from($data['email'])
                ->replyTo($data['email'])
                ->to($adminMail)
                ->subject($data['subject'])
                ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', $translator->trans('contact.flash'));

            return $this->redirectToRoute('contact');
        }

        return $this->render('HomePage/contact-us.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}