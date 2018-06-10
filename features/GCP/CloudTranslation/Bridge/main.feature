Feature: ##

  Scenario: I want to create a new CloudTranslationBridge
    Given I create a new CloudTranslationBridge using credentials "credentials.json"
    Then I want to translate a new entry "Bien le bonjour" using the following locale "it"
    Then The result should be defined.
