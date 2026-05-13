<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class LegalController extends AbstractController
{
    /**
     * Политика конфіденціальності
     */
    public function privacyPolicyAction(Request $request)
    {
        $host = $_ENV['BASE_HOST'];
        return $this->render('static/privacy_policy.html.twig', [
            'site_name' => 'Chronolog',
            'site_url' => $host,
            'contact_email' => 'info@chronolog.com.ua'
        ]);
    }

    /**
     * Умови використання
     */
    public function termsOfServiceAction(Request $request)
    {
        return $this->render('legal/terms_of_service.html.twig', [
            'site_name' => 'Chronolog',
            'site_url' => $this->getParameter('base_host'),
        ]);
    }

    /**
     * Про нас
     */
    public function aboutAction(Request $request)
    {
        return $this->render('legal/about.html.twig', [
            'site_name' => 'Chronolog',
            'site_url' => $this->getParameter('base_host'),
        ]);
    }

    /**
     * Контакти
     */
    public function contactAction(Request $request)
    {
        return $this->render('static/contact.html.twig', [
            'site_name' => 'Chronolog',
        ]);
    }

    /**
     * Відповідь на контактну форму
     */
    public function submitContactAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            // Валідація
            if (empty($name) || empty($email) || empty($message)) {
                $this->addFlash('error', 'Будь ласка заповніть всі поля');
                return $this->redirectToRoute('contact');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', 'Невірна адреса електронної пошти');
                return $this->redirectToRoute('contact');
            }

            // Надіслати листа
            try {
                $mailer = $this->get('mailer');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Нова заявка: ' . $subject)
                    ->setFrom($email)
                    ->setTo($this->getParameter('mailer_user'))
                    ->setBody(
                        "Ім'я: $name\n" .
                        "Email: $email\n" .
                        "Тема: $subject\n\n" .
                        "Повідомлення:\n" . $message
                    );

                $mailer->send($message);
                $this->addFlash('success', 'Дякуємо! Ми отримали вашу заявку');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Помилка при надіслані повідомлення');
            }
        }

        return $this->redirectToRoute('contact');
    }
}
