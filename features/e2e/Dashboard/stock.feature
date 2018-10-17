Feature: As a registered and validated user, I should be able to create a new stock.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      | active       |

  Scenario Outline: [FAILURE] Invalid informations
    Given I log myself using the following username "<username>" and password "<password>"
    And I am on "<dashboard_path>"
    When I go to "<stock_path>"
    Then I should see "<title>"
    Examples:
      | username   | password   | dashboard_path | stock_path                   | title                           |
      | HelloWorld | Ie1FDLGHW  | /en/dashboard  | /en/dashboard/stock/creation | MarketReminder - Create a stock |
      | Titi       | Ie1FDLTITI | /fr/dashboard  | /fr/dashboard/stock/creation | MarketReminder - Créer un stock |

  Scenario Outline: [SUCCESS] Valid informations
    Given I log myself using the following username "<username>" and password "<password>"
    And I am on "<dashboard_path>"
    When I go to "<stock_path>"
    Then I should see "<title>"
    Examples:
      | username   | password   | dashboard_path | stock_path                   | title                           |
      | HelloWorld | Ie1FDLGHW  | /en/dashboard  | /en/dashboard/stock/creation | MarketReminder - Create a stock |
      | Titi       | Ie1FDLTITI | /fr/dashboard  | /fr/dashboard/stock/creation | MarketReminder - Créer un stock |