Feature: As the application use Google Cloud Platform
  It's important to test the CloudTranslation implementation.

  Scenario: I want to create a new CloudTranslationBridge along with a CloudTranslationClient and translate a single entry.
    Given I create a new CloudTranslationBridge using credentials "credentials.json"
    Then I create a new CloudTranslationClient
    Then I want to translate a new entry "Bien le bonjour" using the following locale "it"
    Then The result should be defined.

