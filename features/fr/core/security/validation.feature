Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      |

  Scenario: I want to validate myself using a wrong token.
    Given I am on "/fr/validation/EdFEDNRanuLs"
    Then I should be on "/fr/"
    And I should see "Ce token n'existe pas ou a déjà été validé, veuillez réssayer !"

  Scenario: I want to validate myself using a right token.
    Given I am on "/fr/validation/ToFEGARRdjLs2"
    Then I should be on "/fr/"
    And I should see "Compte validé, il est temps de commencer votre aventure !"

  Scenario: I want to validate myself twice with the same token.
    Given I am on "/fr/validation/EdFEDNRanuLs5"
    Then I should be on "/fr/"
    And I should see "Ce token n'existe pas ou a déjà été validé, veuillez réssayer !"
