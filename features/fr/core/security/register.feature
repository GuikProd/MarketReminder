Feature: As a normal user, I want to be able to register myself and create a new account,
  during this phase, I want to be able to add a profile image.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |
    When I am on "/fr/register"
    Then I should see "S'enregistrer"

  Scenario: I want to register myself using a too small username.
    Then I fill in "register_username" with "to"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop courte !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large username.
    Then I fill in "register_username" with "tototototototototototototo"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large email.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "totototototototototototototototototototototototototototototototo@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200

  Scenario: I want to register myself using a wrong email.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto.com"
    And I fill in "register_password" with "Ie1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And the response status code should be 200

  Scenario: I want to register myself using a too small password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Toto"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop courte !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLPIe1FDLDLPIe1FDLDLP"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Cette valeur est trop longue !"
    And the response status code should be 200

  Scenario: I want to register myself using a right profile image.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLMR"
    And I attach the file "test.png" to "register_profileImage"
    And I press "Créer un compte"
    Then I should be on "/fr/"
    And the response status code should be 200

  Scenario: I want to register myself using a wrong profile image.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLMR"
    And I attach the file "test_III.gif" to "register_profileImage"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Ce format d'image est interdit, veuillez recommencer votre saisie."
    And the response status code should be 200

  Scenario: I want to register myself using a wrong image which contains wrong labels.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_password" with "Ie1FDLDLMR"
    And I attach the file "test_money.jpg" to "register_profileImage"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "L'image soumise contient du contenu inadapté, veuillez recommencer !"
    And the response status code should be 200

  Scenario: I want to register myself with an account that already exist.
    Then I fill in "register_username" with "HelloWorld"
    And I fill in "register_email" with "hello@gmail.com"
    And I fill in "register_password" with "Ie1FDLGHW"
    And I press "Créer un compte"
    Then I should be on "/fr/register"
    And I should see "Les identifiants renseignés semblent invalides, veuillez réessayer."
    And the response status code should be 200
