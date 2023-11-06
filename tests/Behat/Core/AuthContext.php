<?php

namespace App\Tests\Behat\Core;

use App\DependencyInjection\Repository\User\ConfirmationCodeRepositoryDI;
use App\DependencyInjection\Repository\User\UserRepositoryDI;
use App\Entity\User\ConfirmationCode;
use App\Tests\Utils\AssertUtils;
use App\Tests\Utils\Validation\ObjectSchema;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class AuthContext extends BaseContext
{
    use UserRepositoryDI;
    use ConfirmationCodeRepositoryDI;
    use AuthContextDI;

    public mixed $registerResponse;

    /** @var array|\stdClass[] */
    private array $users = [];

    /** @var \stdClass|ConfirmationCode */
    private ConfirmationCode|\stdClass $confirmationCode;

    protected ResponseInterface|null $response = null;

    private static array $accountsMap = [
        'ROLE_CUSTOMER'      => [
            'fullName' => 'Behat User',
            'phoneNumber' => '5555555555',
            'username' => 'behat.user@ryde.ro',
            'password' => 'talent12',
            'confirmPassword' => 'talent12',
        ],
        'ROLE_CAR_OWNER' => [
            'fullName' => 'Behat Car Owner',
            'phoneNumber' => '1111111111',
            'username' => 'behat.car_owner@ryde.ro',
            'password' => 'talent12',
            'confirmPassword' => 'talent12',
        ],
    ];

    /**
     * @When /^I try to register with (ROLE_CUSTOMER) user credentials$/
     */
    public function iTryToRegisterWithCLIENTUserCredentials(string $role)
    {
        $options['json'] = [
            'fullName' => self::$accountsMap[$role]['fullName'],
            'phoneNumber' => self::$accountsMap[$role]['phoneNumber'],
            'email' => self::$accountsMap[$role]['username'],
            'role' => $role,
            'password' => self::$accountsMap[$role]['password'],
            'confirmPassword' => self::$accountsMap[$role]['confirmPassword']
        ];

        $response = $this->getClient()->post("/api/v1/register", $options);
        $this->registerResponse = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When /^I try to register with (ROLE_CAR_OWNER) user credentials$/
     */
    public function iTryToRegisterWithROLE_CAR_OWNERUserCredentials(string $role)
    {
        $options['json'] = [
            'fullName' => self::$accountsMap[$role]['fullName'],
            'phoneNumber' => self::$accountsMap[$role]['phoneNumber'],
            'email' => self::$accountsMap[$role]['username'],
            'role' => $role,
            'password' => self::$accountsMap[$role]['password'],
            'confirmPassword' => self::$accountsMap[$role]['confirmPassword']
        ];

        $response = $this->getClient()->post("/api/v1/register", $options);
        $this->registerResponse = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When /^I login with a (ROLE_CUSTOMER|ROLE_CAR_OWNER) user/
     */
    public function iLoginWithAROLE_CUSTOMERuser(string $role)
    {
        $api = "/api/v1/login";
        $options['json'] = [
            'username' => self::$accountsMap[$role]['username'],
            'password' => self::$accountsMap[$role]['password'],
        ];

        $response = $this->getClient()->post($api, $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);

        $this->iReceiveAValidAuthenticationToken();
    }

    /**
     * @Then /^I receive a valid authentication token$/
     */
    public function iReceiveAValidAuthenticationToken()
    {
        AssertUtils::verifyObjectSchema($this->responseData, ObjectSchema::fromArray([
            'token'        => 'string'
        ]));
        $this->addDefaultHeader('Authorization', "Bearer " . $this->responseData->token);
    }

    /**
     * @When User logs in Continue with Facebook account for the first time
     */
    public function userLogsInContinueWithFacebookAccountForTheFirstTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];

        $response = $this->getClient()->post('/api/v1/login/facebook', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When User logs in Continue with Facebook account another time
     */
    public function userLogsInContinueWithFacebookAccountAnotherTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];
        $response = $this->getClient()->post('/api/v1/login/facebook', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When User logs in Continue with Google account for the first time
     */
    public function userLogsInContinueWithGoogleAccountForTheFirstTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];
        $response = $this->getClient()->post('/api/v1/login/google', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When User logs in Continue with Google account another time
     */
    public function userLogsInContinueWithGoogleAccountAnotherTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];
        $response = $this->getClient()->post('/api/v1/login/google', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When User logs in Continue with Apple account for the first time
     */
    public function userLogsInContinueWithAppleAccountForTheFirstTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];
        $response = $this->getClient()->post('/api/v1/login/apple', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When User logs in Continue with Apple account another time
     */
    public function userLogsInContinueWithAppleAccountAnotherTime()
    {
        $options['json'] = [
            'token' => 'EAAJk81gNMf4BAI8bBYOTuA5ZAhfyN3tKpTIMg5n0zTG3z94loXpZARddehrytv81jjzS3Br5LiQqnx9ZA9STdgv0j9h0NN4GO8ksH7qnZAegtXTgSdEPfa8TQdSLCKxLtMTVn04XtxjTuo9lJFDDVVOp67BlF7ZBZB1HBZBoPRXVW9xY6VaRwVTaR0AfhhpkxH9p0PVwtKCD3OOsNjak7ksWqpFDo4BukVn5YOo0k1yJVczBPZBpAvAhg7KsWnqZCVVsZD'
        ];
        $response = $this->getClient()->post('/api/v1/login/apple', $options);
        $this->responseData = AssertUtils::decodeSuccessResponse($response);
    }

    /**
     * @When /^User send request for reset password$/
     */
    public function userSendRequestForResetPassword()
    {
        $options['json'] = [
            'email' => self::$accountsMap['ROLE_CUSTOMER']['username'],
        ];
        $this->response = $this->getClient()->post("/api/v1/reset-password", $options);
        Assert::assertContains($this->response->getStatusCode(),[200,208], 'expected 200 but get response' . $this->response->getStatusCode());
    }

    /**
     * @Then /^User received the otp for change password$/
     */
    public function userReceivedTheOtpForChangePassword()
    {
        $user = $this->userRepository->findOneByEmail(self::$accountsMap['ROLE_USER']['username']);

        $this->confirmationCode = $this->confirmationCodeRepository->findActivePasswordResetCode($user);
        Assert::assertGreaterThan(99999, (int)$this->confirmationCode->getCode(), "Mai putin de 6 cifre");
        Assert::assertLessThan(1000000, (int)$this->confirmationCode->getCode(), "Mai mult de 6 cifre");
        Assert::assertEquals("PasswordReset", $this->confirmationCode->getAction(), "Actiunea nu e corecta");
        Assert::assertEquals($this->confirmationCode->getCreatedAt()->modify('1 min'), $this->confirmationCode->getExpireAt(), "Codul expira in mai mult/putin de 1 minute");
    }

    /**
     * @Then /^User verified the otp for change password$/
     */
    public function userVerifiedTheOtpForChangePassword()
    {
        $options['json'] = [
            'otp' => $this->confirmationCode->getCode(),
            'action' => ConfirmationCode::ACTION_PASSWORD_RESET
        ];

        $response = $this->getClient()->post("/api/v1/verify-otp", $options);
        Assert::assertContains($response->getStatusCode(),[200,208], 'expected 200 but get response' . $response->getStatusCode());
    }
}