Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      |

  Scenario: I want to validate myself using a wrong token.
    Given I am on "/en/validation/EdFEDNRanuLs"
    Then I should be on "/en/"
    And I should see "Oops, looks like the validation token isn't valid or has already been validated, please retry !"

  Scenario: I want to validate myself using a right token.
    Given I am on "/en/validation/EdFEDNRanuLs5"
    Then I should be on "/en/"
    And I should see "Account validated ! Time to start your stock management !"

  Scenario: I want to validate myself twice with the same token.
    Given I am on "/en/validation/EdFEDNRanuLs5"
    Then I should be on "/en/"
    And I should see "Oops, looks like the validation token isn't valid or has already been validated, please retry !"
