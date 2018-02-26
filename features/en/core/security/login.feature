Feature: As a registered user, I want to log myself and being able to see a restricted area.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken    | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | AZERTYQWERTY       | true       | true   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | helloworldfromTiti | false      | false  |
    When I am on "/en/login"
    Then I should see "Login"

  Scenario: I want to log myself using a wrong username.
    Then I fill in "_username" with "HelloW"
    Then I fill in "_password" with "Ie1FDLGHW"
    And I press "Login"
    Then I should be on "/en/login"
    And I should see "The submitted credentials seems invalid, please verify and retry."
