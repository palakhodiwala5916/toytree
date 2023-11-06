<?php

namespace App\Controller\Api\V1;

use App\Controller\AbstractApiController;
use App\DependencyInjection\Framework\ContainerDI;
use App\DependencyInjection\Framework\ValidatorDI;
use App\DependencyInjection\Repository\User\ConfirmationCodeRepositoryDI;
use App\DependencyInjection\Repository\User\UserRepositoryDI;
use App\DependencyInjection\Service\OAuth\OAuthClientServiceDI;
use App\DependencyInjection\Service\SecurityServiceDI;
use App\Entity\User\ConfirmationCode;
use App\Entity\User\User;
use App\Exception\SoftException;
use App\Service\Security\OAuth\OAuthClientService;
use App\VO\Protocol\Api\Security\GuestVO;
use App\VO\Protocol\Api\Security\LoginBody;
use App\VO\Protocol\Api\Security\LoginData;
use App\VO\Protocol\Api\Security\RegisterBody;
use App\VO\Protocol\Api\Security\ResendOTPBody;
use App\VO\Protocol\Api\Security\SocialLoginVO;
use App\VO\Protocol\Api\Security\VerifyOTPBody;
use App\VO\Protocol\Api\User\ResetPasswordVO;
use App\VO\Protocol\ApiResponseBody;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Symfony\Component\Validator\Constraints as Assert;

#[Rest\Route("/api/v1", name: "api_v1_")]
final class SecurityClientController extends AbstractApiController
{
    use SecurityServiceDI;
    use ValidatorDI;
    use ContainerDI;
    use OAuthClientServiceDI;
    use UserRepositoryDI;
    use ConfirmationCodeRepositoryDI;

    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer,Environment $environment, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->twig = $environment;
    }

    /**
     * @throws \App\Exception\SoftException
     */
    // <editor-fold desc="POST /api/v1/login">
    #[Rest\Post("/login", name: "login")]
    #[Rest\View(serializerGroups: ["api","client:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array", items: new OA\Items(ref: new Model(type: LoginData::class, groups: ["api", "client:view"]))
                )
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "username", description: "User name", type: "string"),
        new OA\Property(property: "password", description: "Password", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function login(Request $request, LoginBody $body): LoginData
    {
        return $this->securityService->authenticateByUsernameAndPassword($body->username, $body->password);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/login/facebook">
    #[Rest\Post("/login/facebook", name: "facebook_login")]
    #[Rest\View(serializerGroups: ["api", "customer:view","carOwner:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array", items: new OA\Items(ref: new Model(type: LoginData::class, groups: ["api", "client:view"]))
                )
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User facebook login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "token", description: "User facebook token", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function facebookLogin(SocialLoginVO $body): LoginData
    {
        $errors = $this->validator->validate(
            $body->token, new Assert\NotBlank(message: 'login.request.token.required')
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }
        $userData = $this->oAuthClientService->verifyToken($body->token, OAuthClientService::FACEBOOK);

        $user = $this->userRepository->findOneByFacebookId($userData->userId);
        if (!$user) {
            $user = $this->securityService->createSocialUser($userData, OAuthClientService::FACEBOOK);

            return $this->securityService->authenticate($user);
        }

        return $this->securityService->authenticate($user);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/login/google">
    #[Rest\Post("/login/google", name: "google_login")]
    #[Rest\View(serializerGroups: ["api", "customer:view","carOwner:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array", items: new OA\Items(ref: new Model(type: LoginData::class, groups: ["api", "client:view"]))
                )
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User google login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "token", description: "User google token", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function googleLogin(SocialLoginVO $body): LoginData
    {
        $errors = $this->validator->validate(
            $body->token, new Assert\NotBlank(message: 'login.request.token.required')
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }
        $userData = $this->oAuthClientService->verifyToken($body->token, OAuthClientService::GOOGLE);

        $user = $this->userRepository->findOneByGoogleId($userData->userId);
        if (!$user) {
            $user = $this->securityService->createSocialUser($userData, OAuthClientService::GOOGLE);

            return $this->securityService->authenticate($user);
        }

        return $this->securityService->authenticate($user);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/login/apple">
    #[Rest\Post("/login/apple", name: "apple_login")]
    #[Rest\View(serializerGroups: ["api", "customer:view","carOwner:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array", items: new OA\Items(ref: new Model(type: LoginData::class, groups: ["api", "client:view"]))
                )
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User apple login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "token", description: "User apple token", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function appleLogin(SocialLoginVO $body): LoginData
    {
        $errors = $this->validator->validate(
            $body->token, new Assert\NotBlank(message: 'login.request.token.required')
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }
        $userData = $this->oAuthClientService->verifyToken($body->token, OAuthClientService::APPLE);

        $user = $this->userRepository->findOneByAppleId($userData->userId);
        if (!$user) {
            $user = $this->securityService->createSocialUser($userData, OAuthClientService::APPLE);

            return $this->securityService->authenticate($user);
        }

        return $this->securityService->authenticate($user);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/reset-password">
    #[Rest\Post("/reset-password", name: "reset_password")]
    #[Rest\View(serializerGroups: ["api", "customer:view","carOwner:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User apple login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "email", description: "User email address", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function resetPassword(ResetPasswordVO $body): ApiResponseBody
    {
        $errors = $this->validator->validate(
            $body->email,
            [   new Assert\NotBlank(message: 'user.reset.confirmation.email.required'),
                new Assert\Email(message: 'user.reset.confirmation.email.invalid')
            ]
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }

        $user = $this->userRepository->findOneByEmail($body->email);

        if(!$user){
            throw new SoftException('user.reset.confirmation.email.invalid');
        }

        $code = random_int(100000, 999999);
        $confirmationCode = new ConfirmationCode();
        $confirmationCode->setCode($code);
        $confirmationCode->setAction($confirmationCode::ACTION_PASSWORD_RESET);
        $confirmationCode->setUser($user);
        $this->entityManager->persist($confirmationCode);
        $this->entityManager->flush();

        $this->sendOTPEmail($user,$code);

        return new ApiResponseBody(true, null);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/register">
    #[Rest\Post("/register", name: "register")]
    #[Rest\View(serializerGroups: ["api", "client:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array",  items: new OA\Items(ref: new Model(type: User::class, groups: ["api", "client:view"])))
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User details", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "fullName", type: "string"),
        new OA\Property(property: "phoneNumber", type: "string"),
        new OA\Property(property: "role", type: "string"),
        new OA\Property(property: "email", type: "string"),
        new OA\Property(property: "password", type: "string"),
        new OA\Property(property: "confirmPassword", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function register(Request $request, RegisterBody $body): User
    {
        $constraints = new Assert\Collection(
            [
                'fullName' => new Assert\NotBlank(message: 'register.request.full_name.required'),
                'phoneNumber' => new Assert\NotBlank(message: 'register.request.phone_number.required'),
                'role' => new Assert\NotBlank(message: 'register.request.role.required'),
                'email' => new Assert\NotBlank(message: 'register.request.username.required'),
                'password' => [
                    new Assert\NotBlank(message: 'register.request.password.required'),
                    new Assert\Length(min: 8, minMessage: 'register.request.password.too_short')
                ],
                'confirmPassword' => [
                    new Assert\NotBlank(message: 'register.request.confirm_password.required'),
                    new Assert\Length(min: 8, minMessage: 'register.request.password.too_short'),
                ],
            ]
        );

        $errors  = $this->validator->validate(
            $request->request->all(), $constraints
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }

        if ($body->password != $body->confirmPassword) {
            throw new SoftException('register.request.password.not_match');
        }

        $body->otp = mt_rand(100000, 999999);
        $user = $this->securityService->createUser($body);

        $this->sendEmail($user, $body->otp);

        return $user;
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/verify-otp">
    #[Rest\Post("/verify-otp", name: "verify_otp")]
    #[Rest\View(serializerGroups: ["api","customer:view","carOwner:view"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'User verify otp for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', type: "array",  items: new OA\Items(ref: new Model(type: LoginData::class, groups: ["api", "client:view"])))
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User details", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "otp", description: "OTP", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function verifyOTP(Request $request, VerifyOTPBody $body)
    {
        $constraints = new Assert\Collection(
            [
                'otp' => new Assert\NotBlank(message: 'verifyOTP.request.otp.required'),
                'action' => new Assert\NotBlank(message: 'verifyOTP.request.action.required'),
            ]
        );

        $errors  = $this->validator->validate(
            $request->request->all(), $constraints
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }

        $confirmationCode = $this->confirmationCodeRepository->findOneByCode($body->otp);
        if (!$confirmationCode) {
            throw new SoftException('verifyOTP.request.incorrect.code');
        }

        if ($confirmationCode->getAction() != $body->action) {
            throw new SoftException('verifyOTP.request.incorrect.code');
        }

        if ($confirmationCode->getExpireAt() < \DateTimeService::now()) {
            throw new SoftException('verifyOTP.request.expired.code');
        }

        if($body->action == ConfirmationCode::ACTION_PASSWORD_RESET) {
            return new ApiResponseBody(true, null, null ,null);
        }

        if ($body->action == ConfirmationCode::ACTION_PHONE_CONFIRMATION) {
            $user = $confirmationCode->getUser();
            $user->setIsPhoneVerified(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new ApiResponseBody(true, null);
        }

        $user = $confirmationCode->getUser();
        $user->setIsEmailVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->securityService->authenticate($user);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/user/resend-otp/{email}">
    #[Rest\Post("/resend-otp", name: "resend_otp")]
    #[Rest\View(serializerGroups: ["api"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns otp for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User details", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "email", description: "Email", type: "string"),
        new OA\Property(property: "action", description: "Action (EmailConfirmation / PasswordReset)", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'Login / Register')]
    public function resendOTP(Request $request, ResendOTPBody $body): ApiResponseBody
    {
        $constraints = new Assert\Collection(
            [
                'email' => new Assert\NotBlank(message: 'resendOTP.request.email.required'),
                'action' => new Assert\NotBlank(message: 'resendOTP.request.action.required'),
            ]
        );

        $errors  = $this->validator->validate(
            $request->request->all(), $constraints
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }

        $code = mt_rand(100000, 999999);
        $user = $this->userRepository->findOneByEmail($body->email);
        if (!$user) {
            throw new SoftException('resendOTP.request.email.not_found');
        }

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->setCode($code);
        $confirmationCode->setUser($user);
        $confirmationCode->setAction($body->action);
        $this->entityManager->persist($confirmationCode);
        $this->entityManager->flush();

        $this->sendOTPEmail($user,$code);

        return new ApiResponseBody(true, null);
    }
    // </editor-fold>

    // <editor-fold desc="POST /api/v1/login/guest">
    #[Rest\Post("/login/guest", name: "guest_login")]
    #[Rest\View(serializerGroups: ["api"])]
    #[ParamConverter('body', converter: 'fos_rest.request_body')]
    #[OA\Response(
        response: 200,
        description: 'Returns User detail for the requesting application',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: "boolean"),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: "string"),
                    new OA\Property(property: 'refreshToken', type: "string"),
                    new OA\Property(property: 'user',properties:[
                        new OA\Property(property: 'id', type: "string"),
                        new OA\Property(property: 'firstName', type: "string"),
                        new OA\Property(property: 'lastName', type: "string"),
                        new OA\Property(property: 'email', type: "string"),
                        new OA\Property(property: 'phoneNumber', type: "string"),
                        new OA\Property(property: 'roles', type: "array", items: new OA\Items(type: "string")),
                        new OA\Property(property: 'otp', type: "string"),
                    ]),
                ], type: "object"
                )
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(description: "User guest login detail", required: true, content: new OA\JsonContent(properties: [
        new OA\Property(property: "username", description: "User name", type: "string"),
        new OA\Property(property: "password", description: "Password", type: "string"),
    ], type: "object"))]
    #[OA\Tag(name: 'User Login / Register')]
    public function guestLogin(GuestVO $body): LoginData
    {
        $body->username = $this->securityService->generateGuestEmail();
        $body->password = $this->securityService->generateRandomPassword(8);

        $constraint = new Assert\Collection(
            [
                'username' => new Assert\NotBlank(message: 'register.request.username.required'),
                'password' => [
                    new Assert\NotBlank(message: 'register.request.password.required'),
                    new Assert\Length(min: 8, minMessage: 'register.request.password.too_short')
                ]
            ]
        );

        $errors  = $this->validator->validate(
            [
                'username' => $body->username,
                'password' => $body->password
            ], $constraint
        );

        if ($errors->count() > 0){
            throw new SoftException($errors[0]->getMessage());
        }

        $this->validatorService->validateRequestBody($body);

        $user = $this->securityService->createClientUser($body, "guest");

        return $this->securityService->authenticate($user);
    }
    // </editor-fold>

    public function sendEmail($user, $otp)
    {
        $message = new Email();
        $message->subject('Welcome to Super Token!');
        $message->from($this->container->getParameter('app.admin_mail'));
        $message->to($user->getEmail());
        $message->html(
            $this->twig->render(
                'emails/user_otp_email.html.twig',
                [
                    'name' =>  $user->getFullName(),
                    'email' => $user->getEmail(),
                    'otp'=> $otp
                ]
            ),
        );
        $this->mailer->send($message);
    }

    public function sendOTPEmail($user,$code)
    {
        $message = new Email();
        $message->subject('OTP - Super Token!');
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
