<?php

namespace App\Service;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotificationService
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var string
     */
    private $emailSender;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var string|null
     */
    private $emailPerso;

    public function __construct(
        MailerInterface $mailer,
        ?string $emailSender,
        ?string $emailPerso,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->mailer = $mailer;
        $this->emailSender = $emailSender;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->emailPerso = $emailPerso;
    }

    public function sendEmailWhenContactAdmin(Contact $contact): void
    {
        $template = 'mailing/contact.html.twig';

        $templateEmail = (new TemplatedEmail())
            ->from(new Address($this->emailSender, 'MISSION C2C'))
            ->to($this->emailPerso)
            ->subject('MISSION C2C - Prise de contact ')
            ->htmlTemplate($template)
            ->context([
                'contact' => $contact,
                'homepage' => $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

        $this->mailer->send($templateEmail);

    }
}
