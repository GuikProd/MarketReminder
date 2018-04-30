Feature: As a registered user, I should be able to reset my password with a reset token.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken | resetPasswordToken | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5   | EdFEDNRanuLs5      | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2   | RPFDNANRnudr4      | 0          | 0      | active       |

  Scenario: I want to reset my password using a wrong token
    Given I am on "/en/reset-password/RPFDNANRnudr2"
    Then I should be on "/en/"
    And I should see "MarketReminder - Inventory Management"

  Scenario: I want to reset my password using a good token and a wrong password
    Given I am on "/en/reset-password/EdFEDNRanuLs5"
    Then I should be on "/en/reset-password/EdFEDNRanuLs5"
    And I fill in "reset_password_password_first" with "Ie1FDLH"
    And I fill in "reset_password_password_second" with "Ie1FDLHW"
    Then I press "reset"
    And I should be on "/en/reset-password/EdFEDNRanuLs5"
    And I should see "Passwords do not match, please try again."

  Scenario: I want to reset my password using a good token and a good password
    Given I am on "/en/reset-password/EdFEDNRanuLs5"
    Then I should be on "/en/reset-password/EdFEDNRanuLs5"
    And I fill in "reset_password_password_first" with "Ie1FDLHW"
    And I fill in "reset_password_password_second" with "Ie1FDLHW"
    Then I press "reset"
    And I should be on "/en/"
    Then I should see "MarketReminder - Inventory Management"
    And I should see "Your password has been changed, you can now login."
