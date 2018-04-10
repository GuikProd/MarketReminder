Feature: As a registered user, I should be able to reset my password if I lost it.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |
    And I am on "/fr/reset-password/ask"
    And I should see "MarketReminder - Réinitialiser son mot de passe"

  Scenario: I want to reset my password without having an account.
    Then I fill in "ask_reset_password_username" with "Toto"
    Then I fill in "ask_reset_password_email" with "toto@gmail.com"
    And I press "Envoyer"
    Then I should be on "/fr/reset-password/ask"
    And I should see "Les idenfiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer !"

  Scenario: I want to reset my password using a wrong username.
    Then I fill in "ask_reset_password_username" with "Titu"
    Then I fill in "ask_reset_password_email" with "titi@gmail.com"
    And I press "Envoyer"
    Then I should be on "/fr/reset-password/ask"
    And I should see "Les idenfiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer !"

  Scenario: I want to reset my password using a wrong email.
    Then I fill in "ask_reset_password_username" with "Titi"
    Then I fill in "ask_reset_password_email" with "titu@gmail.com"
    And I press "Envoyer"
    Then I should be on "/fr/reset-password/ask"
    And I should see "Les idenfiants soumis ne permettent pas de trouver un utilisateur, veuillez réessayer !"

  Scenario: I want to reset my password using good credentials.
    Then I fill in "ask_reset_password_username" with "Titi"
    Then I fill in "ask_reset_password_email" with "titi@gmail.com"
    And I press "Envoyer"
    Then I should be on "/fr/"
    And I should see "La demande a bien été enregistrée, un email vous a été envoyé."
