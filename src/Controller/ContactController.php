<?php

namespace Svc\ContactformBundle\Controller;

use Exception;
use Svc\ContactformBundle\Form\ContactType;
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
  public function __construct(private $enableCaptcha, private $contactMail, private $routeAfterSend, private $copyToMe, private TranslatorInterface $translator)
  {
  }

  /**
   * display and handle the contactfrom.
   */
  public function contactForm(Request $request, MailerHelper $mailHelper): Response
  {
    $data = [];
    $data['email'] = '';
    $data['name'] = '';
    try {
      $user = $this->getUser();
      if ($user) {
        if (method_exists($user, 'getEmail')) {
          $data['email'] = $user->getEmail();
        }
        if (method_exists($user, 'getNickname')) {
          $data['name'] = $user->getNickname();
        } else {
          if (method_exists($user, 'getFirstname')) {
            $data['name'] = $user->getFirstname();
          }
          if (method_exists($user, 'getLastname')) {
            $data['name'] .= ' ' . $user->getLastname();
          }
        }
      }
    } catch (Exception) {
    }

    $form = $this->createForm(ContactType::class, $data, [
      'enableCaptcha' => $this->enableCaptcha, 'copyToMe' => $this->copyToMe,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $email = trim($form->get('email')->getData());
      $name = trim($form->get('name')->getData());
      $content = trim($form->get('text')->getData());
      $subject = trim($form->get('subject')->getData());

      $html = $this->renderView('@SvcContactform/contact/MT_contact.html.twig', ['content' => $content, 'name' => $name, 'email' => $email]);
      $text = $this->renderView('@SvcContactform/contact/MT_contact.text.twig', ['content' => $content, 'name' => $name, 'email' => $email]);

      $options = [];
      $options['replyTo'] = $email;
      if ($this->copyToMe and $form->get('copyToMe')->getData()) {
        $options['cc'] = $email;
        $options['ccName'] = $name;
      }

      if ($mailHelper->send($this->contactMail, $this->t('Contact form request') . ': ' . $subject, $html, $text, $options)) {
        $this->addFlash('success', $this->t('Contact request sent.'));

        return $this->redirectToRoute($this->routeAfterSend);
      } else {
        $this->addFlash('error', $this->t('Cannot send contact request, please try it again.'));
      }
    }

    return $this->renderForm('@SvcContactform/contact/contact.html.twig', [
      'form' => $form,
    ]);
  }

  /**
   * private function to translate content in namespace 'ContactformBundle'.
   */
  private function t(string $text, array $placeholder = []): string
  {
    return $this->translator->trans($text, $placeholder, 'ContactformBundle');
  }
}
