Feature: As a normal user, I want to be able to register myself and create a new account,
  during this phase, I want to be able to add a profile image.

  Background:
    Given I am on "/fr/register"
    And I should see "S'enregistrer"

  Scenario: I want to register myself using a too small username.
    Then I fill in "register_username" with "to"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop courte !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large username.
    Then I fill in "register_username" with "tototototototototototototo"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large email.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "totototototototototototototototototototototototototototototototo@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200

  Scenario: I want to register myself using a too small password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Toto"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop courte !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLPIe1FDLDLPIe1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200
