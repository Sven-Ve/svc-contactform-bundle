<?php

declare(strict_types=1);

/*
 * This file is part of the svc/contactform-bundle.
 *
 * (c) 2025 Sven Vetter <dev@sv-systems.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Svc\ContactformBundle\Controller;

use Svc\ContactformBundle\Form\ContactType;
use Svc\ContactformBundle\Service\UserDataExtractor;
use Svc\UtilBundle\Service\MailerHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Controller to create a contact form and send the mail.
 *
 * @author Sven Vetter <dev@sv-systems.com>
 */
class ContactController extends AbstractController
{
    public function __construct(
        private bool $enableCaptcha,
        private string $contactMail,
        private string $routeAfterSend,
        private bool $copyToMe,
        private TranslatorInterface $translator,
        private UserDataExtractor $userDataExtractor,
    ) {
    }

    /**
     * display and handle the contactfrom.
     */
    public function contactForm(Request $request, MailerHelper $mailHelper): Response
    {
        return $this->handleContactForm($request, $mailHelper, '@SvcContactform/contact/contact.html.twig', false);
    }

    /**
     * display and handle the contactform in a modal dialog.
     */
    public function contactFormModal(Request $request, MailerHelper $mailHelper): Response
    {
        return $this->handleContactForm($request, $mailHelper, '@SvcContactform/contact/contact_modal.html.twig', true);
    }

    /**
     * Common handler for contact form processing.
     */
    private function handleContactForm(Request $request, MailerHelper $mailHelper, string $template, bool $isModal): Response
    {
        // Extract user data for pre-filling the form
        $user = null;
        try {
            $user = $this->getUser();
        } catch (\Exception) {
            // Security context not available (e.g., in test environment)
        }
        $data = $this->userDataExtractor->extractUserData($user);

        // CAPTCHA is disabled for modal mode due to Turbo Frame compatibility issues
        // reCAPTCHA v3's async JS loading conflicts with Turbo Frame's dynamic content loading
        // The script may not initialize properly or fires before DOM is ready in the Turbo Frame context
        $form = $this->createForm(ContactType::class, $data, [
            'enableCaptcha' => $isModal ? false : $this->enableCaptcha, 'copyToMe' => $this->copyToMe,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = trim($form->get('email')->getData());
            $name = trim($form->get('name')->getData());
            $content = trim($form->get('text')->getData());
            $subject = trim($form->get('subject')->getData());

            // Validate that required fields are not empty after trimming
            if (empty($email) || empty($name) || empty($content) || empty($subject)) {
                $this->addFlash('error', $this->t('All fields are required.'));

                return $this->render($template, [
                    'form' => $form,
                ]);
            }

            // Sanitize email to prevent header injection
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addFlash('error', $this->t('Please provide a valid email address.'));

                return $this->render($template, [
                    'form' => $form,
                ]);
            }

            // Sanitize subject to prevent header injection
            $subject = preg_replace('/[\r\n\t]/', '', $subject);

            // Prepare template variables
            $templateVars = ['content' => $content, 'name' => $name, 'email' => $email];

            // Render templates only when actually needed
            $html = $this->renderView('@SvcContactform/contact/MT_contact.html.twig', $templateVars);
            $text = $this->renderView('@SvcContactform/contact/MT_contact.text.twig', $templateVars);

            $options = [];
            $options['replyTo'] = $email;
            if ($this->copyToMe and $form->get('copyToMe')->getData()) {
                $options['cc'] = $email;
                $options['ccName'] = $name;
            }

            if ($mailHelper->send($this->contactMail, $this->t('Contact form request') . ': ' . $subject, $html, $text, $options)) {
                $this->addFlash('success', $this->t('Contact request sent.'));

                // For modal with Turbo, return a response that breaks out of the frame and redirects
                if ($isModal) {
                    $response = new Response(
                        '<turbo-frame id="modal-contact-form">' .
                        '<script type="text/javascript">' .
                        'const dialog = document.querySelector("dialog[open]");' .
                        'if (dialog) { dialog.close(); }' .
                        'Turbo.visit("' . $this->generateUrl($this->routeAfterSend) . '");' .
                        '</script>' .
                        '</turbo-frame>'
                    );

                    return $response;
                }

                return $this->redirectToRoute($this->routeAfterSend);
            }
            $this->addFlash('error', $this->t('Cannot send contact request, please try it again.'));

        }

        return $this->render($template, [
            'form' => $form,
        ]);
    }

    /**
     * private function to translate content in namespace 'ContactformBundle'.
     *
     * @param array<mixed> $placeholder
     */
    private function t(string $text, array $placeholder = []): string
    {
        return $this->translator->trans($text, $placeholder, 'ContactformBundle');
    }
}
