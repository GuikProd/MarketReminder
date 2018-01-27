Feature: As a normal user, I want to be able to register myself and create a new account,
  during this phase, I want to be able to add a profile image.

  Background:
    Given I am on "/fr/register"

  Scenario: I want to register myself using a too small username.
    Then I fill in "register_username" with "to"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Créer un compte"