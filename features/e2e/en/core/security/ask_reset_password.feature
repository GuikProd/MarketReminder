Feature: As a registered user, I should be able to reset my password if I lost it.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |
    And I am on "/en/reset-password/ask"
    And I should see "MarketReminder - Reset password"

  Scenario: I want to reset my password without having an account.
    Then I fill in "ask_reset_password_username" with "Toto"
    Then I fill in "ask_reset_password_email" with "toto@gmail.com"
    And I press "To send"
    Then I should be on "/en/reset-password/ask"
    And I should see "Submitted IDs can not find a user, please try again!"

  Scenario: I want to reset my password using a wrong username.
    Then I fill in "ask_reset_password_username" with "Titu"
    Then I fill in "ask_reset_password_email" with "titi@gmail.com"
    And I press "To send"
    Then I should be on "/en/reset-password/ask"
    And I should see "Submitted IDs can not find a user, please try again!"

  Scenario: I want to reset my password using a wrong email.
    Then I fill in "ask_reset_password_username" with "Titi"
    Then I fill in "ask_reset_password_email" with "titu@gmail.com"
    And I press "To send"
    Then I should be on "/en/reset-password/ask"
    And I should see "Submitted IDs can not find a user, please try again!"

  Scenario: I want to reset my password using good credentials.
    Then I fill in "ask_reset_password_username" with "Titi"
    Then I fill in "ask_reset_password_email" with "titi@gmail.com"
    And I press "To send"
    Then I should be on "/en/"
    And I should see "The request has been registered, an email has been sent to you."
