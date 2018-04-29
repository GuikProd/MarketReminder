Feature: As a registered user, I should be able to reset my password with a reset token.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken | resetPasswordToken | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5   | EdFEDNRanuLs5      | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2   | RPFDNANRnudr4      | 0          | 0      | active       |

  Scenario: I want to reset my password using a wrong token
    Given I am on "/fr/reset-password/RPFDNANRnudr2"
    Then I should be on "/fr/"
    And I should see "MarketReminder - Inventory Management"

  Scenario: I want to reset my password using a good token
    Given I am on "/fr/reset-password/EdFEDNRanuLs5"
    Then I should be on "/fr/reset-password/EdFEDNRanuLs5"
    And I fill in "reset_password_password_first" with "Ie1FDLHW"
    And I fill in "reset_password_password_second" with "Ie1FDLHW"
    Then I press "Réinitialiser"
    And I should be on "/fr/"
    Then I should see ""
