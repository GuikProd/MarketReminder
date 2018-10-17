Feature: As a registered user, I should be able to access my dashboard.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |

  Scenario Outline: [FAILURE] User not connected
    When I go to "<dashboard_path>"
    Then I should be on "<login_path>"
    And I should see "<title>"
    Examples:
      | dashboard_path | login_path    | title                         |
      | /en/dashboard  | /en/login     | MarketReminder - To log in    |
      | /fr/dashboard  | /fr/connexion | MarketReminder - Se connecter |

  Scenario Outline: [SUCCESS] User connected
    Given I log myself using the following username "<username>" and password "<password>"
    When I go to "<dashboard_path>"
    Then I should be on "<dashboard_path>"
    And I should see "<title>"
    Examples:
      | username   | password   | dashboard_path | title                      |
      | HelloWorld | Ie1FDLGHW  | /en/dashboard  | MarketReminder - Dashboard |
      | Titi       | Ie1FDLTITI | /fr/dashboard  | MarketReminder - Dashboard |

