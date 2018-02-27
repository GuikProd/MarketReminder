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

  Scenario: I want to log myself using a wrong email.
    Then I fill in "_username" with "hello@test.com"
    Then I fill in "_password" with "Ie1FDLGHW"
    And I press "Login"
    Then I should be on "/en/login"
    And I should see "The submitted credentials seems invalid, please verify and retry."

  Scenario: I want to log myself using a wrong password.
    Then I fill in "_username" with "HelloWorld"
    Then I fill in "_password" with "Ie1FDLG"
    And I press "Login"
    Then I should be on "/en/login"
    And I should see "The submitted credentials seems invalid, please verify and retry."

  Scenario: I want to log myself using the good username.
    Then I fill in "_username" with "HelloWorld"
    Then I fill in "_password" with "Ie1FDLGHW"
    And I press "Login"
    Then I should be on "/en/"

  Scenario: I want to log myself using the good email.
    Then I fill in "_username" with "hello@gmail.com"
    Then I fill in "_password" with "Ie1FDLGHW"
    And I press "Login"
    Then I should be on "/en/"

  Scenario: I want to log myself using the good password.
    Then I fill in "_username" with "HelloWorld"
    Then I fill in "_password" with "Ie1FDLGHW"
    And I press "Login"
    Then I should be on "/en/"
