Feature: User registration
  As a normal user, I should be able to register an account.

  Scenario: User account creation
    When I need to create a user account with the following username "Toto" and email "toto@gmail.com" and password "toto"
    Then I should not be active
    Then I should have a validation token
    Given I validate my account.
    Then I should be active and have a role user.
