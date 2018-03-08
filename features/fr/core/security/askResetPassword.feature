Feature: As a registered user, I should be able to reset my password if I lost it.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      |
    And I am on "/fr/reset-password/ask"
    And I should see "MarketReminder - Reset Password"

  Scenario: I want to reset my password without having an account.
    Then I fill in "askResetPassword_username" with "Toto"
    Then I fill in "askResetPassword_email" with "toto@gmail.com"
    And I press "Demander à réinitialiser votre mot de passe"
    Then I should be on "/fr/reset-password/ask"
    And I should see "Les identifiants renseignés ne correspondent pas à un utilisateur existant, veuillez recommencer votre saisie."
