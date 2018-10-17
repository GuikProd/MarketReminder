Feature: As a registered user, I should be able to reset my password if I lost it.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |

  Scenario Outline: [FAILURE] Wrong credentials
    Given I am on "<reset_path>"
    Then I should see "<title>"
    And I fill in "ask_reset_password_username" with "<username>"
    And I fill in "ask_reset_password_email" with "<email>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | username | email          | title                                           |  button       | path                      | reset_path               | message                                                                                   |
      | Toto     | toto@gmail.com | MarketReminder - Réinitialiser son mot de passe |  Se connecter | /fr/reinitialisation/mdp  | /fr/reinitialisation/mdp | Les identifiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer ! |
      | Titu     | titi@gmail.com | MarketReminder - Réinitialiser son mot de passe |  Se connecter | /fr/reinitialisation/mdp  | /fr/reinitialisation/mdp | Les identifiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer ! |
      | Titi     | titu@gmail.com | MarketReminder - Réinitialiser son mot de passe |  Se connecter | /fr/reinitialisation/mdp  | /fr/reinitialisation/mdp | Les identifiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer ! |
      | Toto     | toto@gmail.com | MarketReminder - Reset password                 |  To log in    | /en/reset-password/ask    | /en/reset-password/ask   | Submitted IDs can not find a user, please try again!                                      |
      | Titu     | titi@gmail.com | MarketReminder - Reset password                 |  To log in    | /en/reset-password/ask    | /en/reset-password/ask   | Submitted IDs can not find a user, please try again!                                      |
      | Titi     | titu@gmail.com | MarketReminder - Reset password                 |  To log in    | /en/reset-password/ask    | /en/reset-password/ask   | Submitted IDs can not find a user, please try again!                                      |

  Scenario Outline: [SUCCESS] Good credentials
    Given I am on "<reset_path>"
    Then I should see "<title>"
    And I fill in "ask_reset_password_username" with "<username>"
    And I fill in "ask_reset_password_email" with "<email>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | username   | email           | title                                           |  button       | path | reset_path               | message                                                         |
      | Titi       | titi@gmail.com  | MarketReminder - Réinitialiser son mot de passe |  Se connecter | /fr/ | /fr/reinitialisation/mdp | La demande a bien été enregistrée, un email vous a été envoyé.  |
      | Titi       | titi@gmail.com  | MarketReminder - Reset password                 |  To log in    | /en/ | /en/reset-password/ask   | The request has been registered, an email has been sent to you. |
      | HelloWorld | hello@gmail.com | MarketReminder - Réinitialiser son mot de passe |  Se connecter | /fr/ | /fr/reinitialisation/mdp | La demande a bien été enregistrée, un email vous a été envoyé.  |
      | HelloWorld | hello@gmail.com | MarketReminder - Reset password                 |  To log in    | /en/ | /en/reset-password/ask   | The request has been registered, an email has been sent to you. |
