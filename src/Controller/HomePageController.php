<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response {

        return $this->render('HomePage/index.html.twig', [
            'message' => 'Prvi kontroler'
        ]);
    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs(): Response {

        return $this->render('HomePage/about-us.html.twig', [
            'message' => 'Prvi kontroler'
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $adminMail = $params->get('MAILER_ADMIN_EMAIL');

            $email = (new Email())
                ->from($data['email'])
                ->to($adminMail)
                ->subject($data['subject'])
                ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent successfully!');

            return $this->redirectToRoute('contact');
        }

        return $this->render('HomePage/contact-us.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}