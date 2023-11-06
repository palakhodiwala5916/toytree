<?php

namespace App\Service\Security;

use App\DependencyInjection\Framework\ContainerDI;
use App\DependencyInjection\Framework\EntityManagerDI;
use App\DependencyInjection\Framework\JWTTokenManagerDI;
use App\DependencyInjection\Framework\RefreshTokenGeneratorDI;
use App\DependencyInjection\Framework\RefreshTokenManagerDI;
use App\DependencyInjection\Framework\StripeClientDI;
use App\DependencyInjection\Framework\TokenStorageDI;
use App\DependencyInjection\Framework\UserPasswordHasherDI;
use App\DependencyInjection\Repository\User\UserRepositoryDI;
use App\DependencyInjection\Service\ValidatorServiceDI;
use App\Entity\User\ConfirmationCode;
use App\Entity\User\User;
use App\Entity\User\UserStatus;
use App\Exception\SoftException;
use App\Service\Security\OAuth\OAuthClientService;
use App\VO\Protocol\Api\Security\LoginData;
use App\VO\Protocol\Api\Security\OAuthClientData;
use App\VO\Protocol\Api\Security\RegisterBody;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityService
{
    use JWTTokenManagerDI;
    use UserPasswordHasherDI;
    use UserRepositoryDI;
    use EntityManagerDI;
    use ValidatorServiceDI;
    use TokenStorageDI;
    use RefreshTokenManagerDI;
    use RefreshTokenGeneratorDI;
    use ContainerDI;
    use StripeClientDI;

    private UrlGeneratorInterface $generate;

    public function __construct(UrlGeneratorInterface $urlGenerator){
        $this->generate = $urlGenerator;
    }

    public function getLoggedInUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * @throws SoftException
     */
    public function authenticateByUsernameAndPassword(string $username, string $password): LoginData
    {
        if (!$user = $this->userRepository->findOneByEmail($username)) {
            throw new SoftException('api.auth.invalid_email');
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $password)) {
            throw new SoftException('api.auth.invalid_password');
        }

        return $this->authenticate($user);
    }

    public function authenticate(User $user): LoginData
    {
        if($user->getStatus() == UserStatus::DeActive){
            throw new SoftException('api.auth.de_active.account');
        }

        $data = new LoginData();
        $data->token = $this->jwtTokenManager->create($user);
        $data->user = $user;

        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, 2592000);
        $this->refreshTokenManager->save($refreshToken);
        $data->refreshToken = $refreshToken->getRefreshToken();

        return $data;
    }

    public function createUser(RegisterBody $body): User
    {
        if ($this->userRepository->findOneByEmail($body->email)) {
            throw new SoftException('register.request.username.exists');
        }

        $user = new User();
        $user->setEmail($body->email);
        $user->setFullName($body->fullName);
        $user->setPhoneNumber($body->phoneNumber);
        $user->setRole($body->role);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $body->password));

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->setCode($body->otp);
        $confirmationCode->setAction($confirmationCode::ACTION_EMAIL_CONFIRMATION);
        $confirmationCode->setUser($user);
        $this->entityManager->persist($confirmationCode);
        $this->entityManager->flush();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $customer = $this->stripeClient->customers->create([
            'name' => $body->fullName,
            'email' => $body->email,
            'phone' => $body->phoneNumber
        ]);
        $user->setStripeCustomerId($customer->id);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function createSocialUser(OAuthClientData $body, string $clientName): User
    {
        $user = new User();

        $user->setEmail($body->email);
        $user->setFullName($body->name);
        $user->setPhoneNumber($body->phone_number);
        $user->setRole('ROLE_USER');
        $user->setCountryCode($body->country_code);
        if ($clientName == OAuthClientService::FACEBOOK) {
            $user->setFacebookId($body->userId);
        } elseif ($clientName == OAuthClientService::GOOGLE) {
            $user->setGoogleId($body->userId);
        } elseif ($clientName == OAuthClientService::APPLE) {
            $user->setAppleId($body->userId);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function generateRandomPassword($length = 25): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($characters), 0, $length);
    }

    public function generateGuestEmail(): string
    {
        return sprintf('guest-%d-%d@%s',
            time(),
            mt_rand(100, 999),
            $this->container->getParameter('app.email_domain')
        );
    }

    /**
     * @throws SoftException
     */
    public function createClientUser($body, $type = null): User
    {
        if ($this->userRepository->findOneByEmail($body->username)) {
            throw new SoftException('register.request.username.exists');
        }

        $user = new User();
        $user->addRole(User::ROLE_USER);
        $user->setEmail($body->username);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $body->password));

        if($type != "guest"){
            $user->setOtp($body->otp);
        } else{
            $user->setIsVerified(true);
        }

        if ($error = $this->validatorService->getValidationError($user)) {
            throw new SoftException($error->getMessage());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}
