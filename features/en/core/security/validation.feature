Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken                   | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | 9b6e77872d98ec5cbcafa96501c03e56  | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | 2d5f2478687a40bd755120bdcb713f01  | 0          | 0      |

  Scenario: I want to validate myself using a wrong token.
    Given I am on "/en/validation/9b6e77872d98ec5cbcafa96501c03"
    Then I should be on "/en/"
    And I should see "Oops, looks like the validation token isn't valid or has already been validated, please retry !"

  Scenario: I want to validate myself using a right token.
    Given I am on "/en/"
    And I go to "/en/validation/2d5f2478687a40bd755120bdcb713f01"
    Then I should be on "/en/"
    And I should see "Account validated ! Time to start your stock management !"

  Scenario: I want to validate myself twice with the same token.
    Given I am on "/en/validation/9b6e77872d98ec5cbcafa96501c03e56"
    Then I should be on "/en/"
    And I should see "Oops, looks like the validation token isn't valid or has already been validated, please retry !"
