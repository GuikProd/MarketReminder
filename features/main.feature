Feature:
  In order to test the main endpoints, we must define the home test then
  testing the different endpoints.

  Scenario: We ask for the homepage
    When i send a request to "/" with method "GET"
    Then the response should be received
    Then the response status code should be 200
