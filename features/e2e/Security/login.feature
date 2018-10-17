Feature: As a registered user, I want to log myself and being able to see a restricted area.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |

  Scenario Outline: [Failure] Wrong password or username
    Given I am on "<login_path>"
    Then I should see "<title>"
    And I fill in "login_username" with "<username>"
    And I fill in "login_password" with "<password>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | username       | password  | title                         | button       | path          | login_path    | message                                      |
      | HelloW         | Ie1FDLGHW | MarketReminder - Se connecter | Se connecter | /fr/connexion | /fr/connexion | Les identifiants soumis semblent invalides ! |
      | hello@test.com | Ie1FDLGHW | MarketReminder - Se connecter | Se connecter | /fr/connexion | /fr/connexion | Les identifiants soumis semblent invalides ! |
      | HelloWorld     | Ie1FDLG   | MarketReminder - Se connecter | Se connecter | /fr/connexion | /fr/connexion | Les identifiants soumis semblent invalides ! |
      | HelloW         | Ie1FDLGHW | MarketReminder - To log in    | To log in    | /en/login     | /en/login     | The credentials submitted seem invalid!      |
      | hello@test.com | Ie1FDLGHW | MarketReminder - To log in    | To log in    | /en/login     | /en/login     | The credentials submitted seem invalid!      |
      | HelloWorld     | Ie1FDLG   | MarketReminder - To log in    | To log in    | /en/login     | /en/login     | The credentials submitted seem invalid!      |

  Scenario Outline: [Success] Valid credentials
    Given I am on "<login_path>"
    Then I should see "<title>"
    And I fill in "login_username" with "<username>"
    And I fill in "login_password" with "<password>"
    And I press "<button>"
    Then I should be on "<path>"
    Examples:
      | username   | password   | title                         |  button       | path           | login_path    |
      | HelloWorld | Ie1FDLGHW  | MarketReminder - Se connecter |  Se connecter | /fr/dashboard  | /fr/connexion |
      | HelloWorld | Ie1FDLGHW  | MarketReminder - To log in    |  To log in    | /en/dashboard  | /en/login     |
      | Titi       | Ie1FDLTITI | MarketReminder - Se connecter |  Se connecter | /fr/dashboard  | /fr/connexion |
      | Titi       | Ie1FDLTITI | MarketReminder - To log in    |  To log in    | /en/dashboard  | /en/login     |