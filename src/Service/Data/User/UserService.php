<?php

namespace App\Service\Data\User;

use App\DependencyInjection\Framework\ContainerDI;
use App\DependencyInjection\Framework\UserPasswordHasherDI;
use App\DependencyInjection\Service\SecurityServiceDI;
use App\Entity\User\ConfirmationCode;
use App\Entity\User\ManualTransmission;
use App\Entity\User\User;
use App\Exception\SoftException;
use App\Service\Data\AbstractCRUDService;
use App\VO\Protocol\Api\Security\ResendOTPBody;
use App\VO\Protocol\Api\User\ChangeEmail;
use App\VO\Protocol\Api\User\ChangePassword;
use App\VO\Protocol\Api\User\ManualTransmissionBody;
use App\VO\Protocol\Api\User\UserBody;
use App\DependencyInjection\Repository\User\UserRepositoryDI;
use App\Repository\User\UserRepository;
use App\VO\Protocol\Api\User\UserProfileBody;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class UserService extends AbstractCRUDService
{
    use UserRepositoryDI;
    use UserPasswordHasherDI;
    use ContainerDI;
    use SecurityServiceDI;

    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer,Environment $environment, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->twig = $environment;
    }

    public function getEntityRepository(): UserRepository
    {
        return $this->userRepository;
    }

    public function updateObjectFields(User $user, UserProfileBody $body): void
    {
        $user->setProfilePicture($this->uploadProfile($body->file));
        $user->setAboutYourself($body->aboutYourself);
        $user->setTorontoOnCanada($body->torontoOnCanada);
        $user->setCity($body->city);
        $user->setToronto($body->toronto);
        $user->setEnglish($body->english);

        $this->saveObject($user);
    }

    public function uploadProfile($profilePicture): string
    {
        $directory = $this->container->getParameter('user_directory');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $fileName = uniqid().'.'.$profilePicture->guessExtension();
        $profilePicture->move(
            $directory,
            $fileName
        );

        return $fileName;
    }

    public function changeEmail(ChangeEmail $body)
    {
        $user = $this->securityService->getLoggedInUser();

        $user->setEmail($body->newEmail);
        $user->setIsEmailVerified(false);

        $code = random_int(100000, 999999);
        $confirmationCode = new ConfirmationCode();
        $confirmationCode->setCode($code);
        $confirmationCode->setAction($confirmationCode::ACTION_EMAIL_CONFIRMATION);
        $confirmationCode->setUser($user);
        $this->entityManager->persist($confirmationCode);
        $this->entityManager->flush();

        $this->sendOTPEmail($user,$code);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function changePassword(ChangePassword $body)
    {
        $user = $this->securityService->getLoggedInUser();
        if (!$this->userPasswordHasher->isPasswordValid($user, $body->currentPassword)) {
            throw new SoftException('user.auth.invalid_current_password');
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $body->newPassword));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @return void
     * @throws SoftException
     */
    public function switchToCustomer():void
    {
        $user = $this->securityService->getLoggedInUser();
        $user->setRole(User::ROLE_CUSTOMER);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @return void
     * @throws SoftException
     */
    public function switchToOwner():void
    {
        $user = $this->securityService->getLoggedInUser();
        $user->setRole(User::ROLE_CAR_OWNER);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updatePhoneNumber(UserBody $body): void
    {
        $user = $this->securityService->getLoggedInUser();

        $user->setPhoneNumber($body->phoneNumber);
        $user->setCountryCode($body->countryCode);
        $user->setIsPhoneVerified(false);

        $otp = mt_rand(100000, 999999);
        $confirmationCode = new ConfirmationCode();
        $confirmationCode->setCode($otp);
        $confirmationCode->setAction(ConfirmationCode::ACTION_PHONE_CONFIRMATION);
        $confirmationCode->setUser($user);
        $user->addConfirmationCode($confirmationCode);

        $this->saveObject($user);
        $this->sendOTPEmail($user, $otp);
    }

    public function changeManualTransmission(ManualTransmissionBody $body)
    {
        $user = $this->securityService->getLoggedInUser();

        $user->setManualTransmission($body->manualTransmission);

        $this->saveObject($user);
    }

    public function sendOTPEmail($user,$code)
    {
        $message = new Email();
        $message->subject('OTP - Ryde!');
        $message->from($this->container->getParameter('app.admin_mail'));
        $message->to($user->getEmail());
        $message->html(
            $this->twig->render(
                'emails/user_resend_otp_email.html.twig',
                [
                    'name' =>  $user->getFullName(),
                    'otp'=> $code
                ]
            ),
        );
        $this->mailer->send($message);
    }

}
