Feature: Authentication feature
  As a User
  I am able to perform authentication actions

  Scenario: user registration module flow
    When I try to register with ROLE_CUSTOMER user credentials
    When I try to register with ROLE_CAR_OWNER user credentials

  Scenario: User logs in Continue with Apple account
    When User logs in Continue with Apple account for the first time
    Then I receive a valid authentication token

  Scenario: User logs in Continue with Apple account
    When User logs in Continue with Apple account another time
    Then I receive a valid authentication token

  Scenario: User logs in Continue with Google account
    When User logs in Continue with Google account for the first time
    Then I receive a valid authentication token

  Scenario: User logs in Continue with Google account
    When User logs in Continue with Google account another time
    Then I receive a valid authentication token

  Scenario: User logs in Continue with Facebook account
    When User logs in Continue with Facebook account for the first time
    Then I receive a valid authentication token

  Scenario: User logs in Continue with Facebook account
    When User logs in Continue with Facebook account another time
    Then I receive a valid authentication token

  Scenario: I login in the application Continue with Email
    When I login with a ROLE_CUSTOMER user
    Then I receive a valid authentication token

  Scenario: I login in the application Continue with Email
    When I login with a ROLE_CAR_OWNER user
    Then I receive a valid authentication token

  Scenario: User request for reset password
    When User send request for reset password
    Then User received the otp for change password
    Then User verified the otp for change password