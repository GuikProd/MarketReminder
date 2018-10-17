Feature: As a normal user, I should be able to see the homepage along with the content

  Scenario Outline: [SUCCESS] Homepage content
    When I go to "<path>"
    Then I should see "<title>"
    And I should see "<footer>"
    Examples:
      | path | title                    | footer                  |
      | /fr/ | MarketReminder - Accueil | Made with ♥ by GuikProd |
      | /en/ | MarketReminder - Home    | Made with ♥ by GuikProd |
