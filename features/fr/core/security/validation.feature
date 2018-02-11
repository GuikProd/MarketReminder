Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken            | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | AZERTYQWERTY               | true       | true   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | helloworldfromTiti67268712 | false      | false  |

  Scenario: I want to validate myself using a wrong token.
    Given I am on "/fr/validation/AZERTYQWER"
    Then I should be on "/fr/"
    And I should see "Ce token n'existe pas, veuillez réssayer !"

  Scenario: I want to validate myself using a right token.
    Given I am on "/fr/validation/helloworldfromTiti67268712"
    Then I should be on "/fr/"
    And I should see "Compte validé, il est temps de commencer votre aventure !"

  Scenario: I want to validate myself twice with the same token.
    Given I am on "/fr/validation/AZERTYQWERTY"
    Then I should be on "/fr/"
    And I should see "Ce token a déjà été validé, veuillez vous connecter ou réinitialiser votre mot de passe."
