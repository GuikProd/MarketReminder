Feature: As a fresh registered used, I should validate my account in order to activate my access.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken                   | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | 9b6e77872d98ec5cbcafa96501c03e56  | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | 2d5f2478687a40bd755120bdcb713f01  | 0          | 0      |
      | Tutu         | Ie1FDLTUTU    | tutu@gmail.com  | 2d5f2478687a40bd75512dadzdzddsqd  | 0          | 0      |

  Scenario Outline: [FAILURE] Wrong token
    Given I am on "<validation_path>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | path | validation_path                                 | message                                                                   |
      | /fr/ | /fr/validation/9b6e77872d98ec5cbcafa96501c03    | Ce token n'existe pas ou a déjà été validé, veuillez réssayer !           |
      | /fr/ | /fr/validation/9b6e77872d98ec5cbcafa96501c03e76 | Ce token n'existe pas ou a déjà été validé, veuillez réssayer !           |
      | /en/ | /en/validation/9b6e77872d98ec5cbcafa96501c03    | This token does not exist or has already been validated, please resubmit! |
      | /en/ | /en/validation/9b6e77872d98ec5cbcafa96501c03e76 | This token does not exist or has already been validated, please resubmit! |

  Scenario Outline: [SUCCESS] Good token
    Given I am on "<validation_path>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | path | validation_path                                 | message                                              |
      | /fr/ | /fr/validation/2d5f2478687a40bd75512dadzdzddsqd | Compte validé, vous pouvez commencer votre gestion ! |
      | /en/ | /en/validation/2d5f2478687a40bd75512dadzdzddsqd | Validated account, you can start your management!    |
      | /fr/ | /fr/validation/2d5f2478687a40bd755120bdcb713f01 | Compte validé, vous pouvez commencer votre gestion ! |
      | /en/ | /en/validation/2d5f2478687a40bd755120bdcb713f01 | Validated account, you can start your management!    |