<?php

namespace Svc\ContactformBundle\Controller;

use Svc\ContactformBundle\Form\ContactType;
use Svc\UtilBundle\Service\MailerHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Controller to create a contact form and send the mail
 *
 * @author Sven Vetter <dev@sv-systems.com>
 */
class ContactController extends AbstractController
{

  private $enableCaptcha;
  private $contactMail;
  private $routeAfterSend;
  private $translator;
  private $copyToMe;

  public function __construct($enableCaptcha, $contactMail, $routeAfterSend, $copyToMe, TranslatorInterface $translator)
  {
    $this->enableCaptcha = $enableCaptcha;
    $this->routeAfterSend = $routeAfterSend;
    $this->contactMail = $contactMail;
    $this->translator = $translator;
    $this->copyToMe = $copyToMe;
  }


  /**
   * display and handlethe contactfrom
   *
   * @param Request $request
   * @param MailerHelper $mailHelper
   * @return Response
   */
  public function contactForm(Request $request, MailerHelper $mailHelper): Response
  {
    $form = $this->createForm(ContactType::class, null, [
      'enableCaptcha' => $this->enableCaptcha, 'copyToMe' => $this->copyToMe
    ]);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
      $email = trim($form->get('email')->getData());
      $name = trim($form->get('name')->getData());
      $content = trim($form->get('text')->getData());
      $subject = trim($form->get('subject')->getData());

      $html = $this->renderView("@SvcContactform/contact/MT_contact.html.twig", ["content" => $content, "name" => $name, "email" => $email]);
      $text = $this->renderView("@SvcContactform/contact/MT_contact.text.twig", ["content" => $content, "name" => $name, "email" => $email]);

      $options = [];
      $options['replyTo'] = $email;
      if ($this->copyToMe and $form->get('copyToMe')->getData()) {
        $options['cc'] = $email;
        $options['ccName'] = $name;
      }

      if ($mailHelper->send($this->contactMail, $this->t("Contact form request") . ": " . $subject, $html, $text, $options)) {
        $this->addFlash("success", $this->t("Contact request sent."));
        return $this->redirectToRoute($this->routeAfterSend);
      } else {
        $this->addFlash("error", $this->t("Cannot send contact request, please try it again."));
      }
    }

    return $this->render('@SvcContactform/contact/contact.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * private function to translate content in namespace 'ContactformBundle'
   *
   * @param string $text
   * @param array $placeholder
   * @return string
   */
  private function t(string $text, array $placeholder = []): string
  {
    return $this->translator->trans($text, $placeholder, 'ContactformBundle');
  }
}