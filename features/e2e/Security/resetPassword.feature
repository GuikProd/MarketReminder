Feature: As a registered user, I should be able to reset my password with a reset token.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken | resetPasswordToken | validated  | active | currentState |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5   | EdFEDNRanuLs5      | 1          | 1      | toValidate   |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2   | RPFDNANRnudr4      | 0          | 0      | active       |

  Scenario Outline: [FAILURE] Wrong token
    Given I am on "<reset_path>"
    Then I should be on "<path>"
    And I should see "<title>"
    Examples:
      | reset_path                       | path | title                    |
      | /en/reset-password/RPFDNANRnudr2 | /en/ | MarketReminder - Home    |
      | /fr/reset-password/RPFDNANRnudr2 | /fr/ | MarketReminder - Accueil |

  Scenario Outline: Good token but wrong password
    Given I am on "<reset_path>"
    And I fill in "reset_password_password_first" with "<password>"
    And I fill in "reset_password_password_second" with "<wrong_password>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | password | wrong_password | button        | reset_path                       | path                             | message                                                                     |
      | Ie1FDLH  | Ie1FDLHW       | reset         | /en/reset-password/EdFEDNRanuLs5 | /en/reset-password/EdFEDNRanuLs5 | Passwords do not match, please try again.                                   |
      | Ie1FDLH  | Ie1FDLHW       | reset         | /en/reset-password/RPFDNANRnudr4 | /en/reset-password/RPFDNANRnudr4 | Passwords do not match, please try again.                                   |
      | Ie1FDLH  | Ie1FDLHW       | Réinitialiser | /fr/reset-password/EdFEDNRanuLs5 | /fr/reset-password/EdFEDNRanuLs5 | Les mots de passes ne correspondent pas, veuillez recommencez votre saisie. |
      | Ie1FDLH  | Ie1FDLHW       | Réinitialiser | /fr/reset-password/RPFDNANRnudr4 | /fr/reset-password/RPFDNANRnudr4 | Les mots de passes ne correspondent pas, veuillez recommencez votre saisie. |

  Scenario Outline: [SUCCESS] Good token and good password
    Given I am on "<reset_path>"
    And I fill in "reset_password_password_first" with "<password>"
    And I fill in "reset_password_password_second" with "<good_password>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | password  | good_password | button        | reset_path                       | path | message                                                                     |
      | Ie1FDLHW  | Ie1FDLHW      | reset         | /en/reset-password/EdFEDNRanuLs5 | /en/ | Your password has been changed, you can now login.                          |
      | Ie1FDLHW  | Ie1FDLHW      | reset         | /en/reset-password/RPFDNANRnudr4 | /en/ | Your password has been changed, you can now login.                          |
      | Ie1FDLHW  | Ie1FDLHW      | Réinitialiser | /fr/reset-password/EdFEDNRanuLs5 | /fr/ | Votre mot de passe a été modifié, vous pouvez dès à présent vous connecter. |
      | Ie1FDLHW  | Ie1FDLHW      | Réinitialiser | /fr/reset-password/RPFDNANRnudr4 | /fr/ | Votre mot de passe a été modifié, vous pouvez dès à présent vous connecter. |
