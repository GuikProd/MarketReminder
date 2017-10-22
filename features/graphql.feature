Feature:
  In order to test the GraphQL api endpoint, we must ensure that
  the main endpoint is accessible then testing different use cases.

  Scenario: I send a request to the GraphQL main endpoint.
    When i send a request to "/api/graphql" with method "GET"
    Then the response should be received
    Then the response status code should be 200

  Scenario: I want to test if the GraphiQL endpoint works.
    When i send a request to "/api/graphiql" with method "GET"
    Then the response should be received
    Then the response status code should be 200
