Feature: As a registered user, I should be able to reset my password with a reset token.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken | resetToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5   |             | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2   |             | 0          | 0      | active       |

  Scenario: I want to reset my password using a wrong token
    Given I am on "/fr/reset-password/"
    And I should see "MarketReminder - Réinitialiser son mot de passe"
