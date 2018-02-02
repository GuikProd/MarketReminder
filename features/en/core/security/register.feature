Feature: As a normal user, I want to be able to register myself and create a new account,
  during this phase, I want to be able to add a profile image.

  Background:
    Given I am on "/en/register"
    And I should see "Registration"

  Scenario: I want to register myself using a too small username.
    Then I fill in "register_username" with "to"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This value is too short !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large username.
    Then I fill in "register_username" with "tototototototototototototo"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This value is too long !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large email.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "totototototototototototototototototototototototototototototototo@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLP"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This value is too long !"
    And the response status code should be 200

  Scenario: I want to register myself using a too small password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Toto"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This value is too short !"
    And the response status code should be 200

  Scenario: I want to register myself using a too large password.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLPIe1FDLDLPIe1FDLDLP"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This value is too long !"
    And the response status code should be 200

  Scenario: I want to register myself using a right profile image.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLMR"
    And I attach the file "test.png" to "register_profileImage"
    And I press "Create an account"
    Then I should be on "/en/"
    And the response status code should be 200

  Scenario: I want to register myself using a wrong profile image.
    Then I fill in "register_username" with "Toto"
    And I fill in "register_email" with "toto@gmail.com"
    And I fill in "register_plainPassword" with "Ie1FDLDLMR"
    And I attach the file "test_III.gif" to "register_profileImage"
    And I press "Create an account"
    Then I should be on "/en/register"
    And I should see "This format is invalid ! Please retry using a valid one !"
    And the response status code should be 200