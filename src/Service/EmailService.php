<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\User;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailVerification(User $user, string $verificationCode): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@saasclient.com')
            ->to($user->getEmail())
            ->subject('Vérifiez votre adresse email - Code de confirmation')
            ->htmlTemplate('emails/email_verification.html.twig')
            ->context([
                'user' => $user,
                'verification_code' => $verificationCode,
                'user_name' => $user->getPrenom() . ' ' . $user->getNom()
            ]);

        $this->mailer->send($email);
    }

    public function sendPasswordReset(User $user, string $resetCode): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@saasclient.com')
            ->to($user->getEmail())
            ->subject('Code de réinitialisation de mot de passe')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context([
                'user' => $user,
                'reset_code' => $resetCode,
                'user_name' => $user->getPrenom() . ' ' . $user->getNom()
            ]);

        $this->mailer->send($email);
    }

    public function sendPhoneVerification(User $user, string $verificationCode): void
    {
        // In a real application, this would send an SMS
        // For now, we'll send an email with the phone verification code
        $email = (new TemplatedEmail())
            ->from('noreply@saasclient.com')
            ->to($user->getEmail())
            ->subject('Code de vérification téléphone')
            ->htmlTemplate('emails/phone_verification.html.twig')
            ->context([
                'user' => $user,
                'verification_code' => $verificationCode,
                'user_name' => $user->getPrenom() . ' ' . $user->getNom(),
                'phone' => $user->getTelephone()
            ]);

        $this->mailer->send($email);
    }
}