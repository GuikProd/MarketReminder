Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken           | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | AZERTYQWERTY              | true       | true   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | helloworldfromTiti9818110 | false      | false  |

  Scenario: I want to validate myself using a wrong token.
    Given I am on "/en/validation/AZERTYQWER"
    Then I should be on "/en/"
    And I should see "Oops, looks like the validation token isn't valid, please retry !"

  Scenario: I want to validate myself using a right token.
    Given I am on "/en/validation/helloworldfromTiti9818110"
    Then I should be on "/en/"
    And I should see "Account validated ! Time to start your stock management !"

  Scenario: I want to validate myself twice with the same token.
    Given I am on "/en/validation/AZERTYQWERTY"
    Then I should be on "/en/"
    And I should see "Oops, this token was already validated ! Please try to log yourself or reset your password."
