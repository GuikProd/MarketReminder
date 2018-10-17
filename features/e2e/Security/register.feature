Feature: As a normal user, I want to be able to register myself and create a new account,
  during this phase, I want to be able to add a profile image.

  Background:
    Given I load following users:
      | username     | plainPassword | email           | validationToken  | validated  | active |
      | HelloWorld   | Ie1FDLGHW     | hello@gmail.com | EdFEDNRanuLs5    | 1          | 1      |
      | Titi         | Ie1FDLTITI    | titi@gmail.com  | ToFEGARRdjLs2    | 0          | 0      |

  Scenario Outline: : [FAILURE] Wrong credentials (username, email or password)
    When I am on "<register_path>"
    Then I should see "<title>"
    Then I fill in "register_username" with "<username>"
    And I fill in "register_email" with "<email>"
    And I fill in "register_password" with "<password>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | register_path      | path               | title             | username                   | email                                                                      | password                    | button            | message                                                             |
      | /en/register       | /en/register       | Create an account | to                         | toto@gmail.com                                                             | Ie1FDLDLP                   | Create an account | This value is too short!                                            |
      | /en/register       | /en/register       | Create an account | tototototototototototototo | toto@gmail.com                                                             | Ie1FDLDLP                   | Create an account | This value is too long!                                             |
      | /en/register       | /en/register       | Create an account | Toto                       | totototototototototototototototototototototototototototototototo@gmail.com | Ie1FDLDLP                   | Create an account | This value is too long!                                             |
      | /en/register       | /en/register       | Create an account | Toto                       | toto.com[at]somerandomtest                                                 | Ie1FDLDLP                   | Create an account | The credentials entered seem invalid, please try again.             |
      | /en/register       | /en/register       | Create an account | Toto                       | toto@gmail.com                                                             | Toto                        | Create an account | This value is too short!                                            |
      | /en/register       | /en/register       | Create an account | Toto                       | toto@gmail.com                                                             | Ie1FDLDLPIe1FDLDLPIe1FDLDLP | Create an account | This value is too long!                                             |
      | /en/register       | /en/register       | Create an account | HelloWorld                 | hello@gmail.com                                                            | Ie1FDLGHW                   | Create an account | The credentials entered seem invalid, please try again.             |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | to                         | toto@gmail.com                                                             | Ie1FDLDLP                   | Créer un compte   | Cette valeur est trop courte !                                      |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | tototototototototototototo | toto@gmail.com                                                             | Ie1FDLDLP                   | Créer un compte   | Cette valeur est trop longue !                                      |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | Toto                       | totototototototototototototototototototototototototototototototo@gmail.com | Ie1FDLDLP                   | Créer un compte   | Cette valeur est trop longue !                                      |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | Toto                       | toto.com[at]somerandomtest                                                 | Ie1FDLDLP                   | Créer un compte   | Les identifiants renseignés semblent invalides, veuillez réessayer. |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | Toto                       | toto@gmail.com                                                             | Toto                        | Créer un compte   | Cette valeur est trop courte !                                      |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | Toto                       | toto@gmail.com                                                             | Ie1FDLDLPIe1FDLDLPIe1FDLDLP | Créer un compte   | Cette valeur est trop longue !                                      |
      | /fr/enregistrement | /fr/enregistrement | Créer un compte   | HelloWorld                 | hello@gmail.com                                                            | Ie1FDLGHW                   | Créer un compte   | Les identifiants renseignés semblent invalides, veuillez réessayer. |

  Scenario Outline: [FAILURE] Wrong image
    When I am on "<register_path>"
    Then I should see "<title>"
    Then I fill in "register_username" with "<username>"
    And I fill in "register_email" with "<email>"
    And I fill in "register_password" with "<password>"
    And I attach the file "<file>" to "register_profileImage"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | register_path       | path               | title             | username | email          | password   | file              | button            | message                                                                |
      | /en/register        | /en/register       | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test_III.gif      | Create an account | This image format is prohibited, please repeat your entry.             |
      | /en/register        | /en/register       | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test_money_II.jpg | Create an account | Submitted image contains inappropriate content, please try again!      |
      | /en/register        | /en/register       | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test_money.jpg    | Create an account | This file exceeds the authorized weight limit, please try again.       |
      | /fr/enregistrement  | /fr/enregistrement | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test_III.gif      | Créer un compte   | Ce format d'image est interdit, veuillez recommencer votre saisie.     |
      | /fr/enregistrement  | /fr/enregistrement | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test_money_II.jpg | Créer un compte   | L'image soumise contient du contenu inadapté, veuillez recommencer !   |
      | /fr/enregistrement  | /fr/enregistrement | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test_money.jpg    | Créer un compte   | Ce fichier dépasse la limite de poids autorisée, veuillez recommencer. |

  Scenario Outline: [SUCCESS] Good credentials without profile image
    When I am on "<register_path>"
    Then I should see "<title>"
    Then I fill in "register_username" with "<username>"
    And I fill in "register_email" with "<email>"
    And I fill in "register_password" with "<password>"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | register_path       | path | title             | username | email          | password   | button            | message                                                                                     |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |

  Scenario Outline: [SUCCESS] Good credentials with profile image
    When I am on "<register_path>"
    Then I should see "<title>"
    Then I fill in "register_username" with "<username>"
    And I fill in "register_email" with "<email>"
    And I fill in "register_password" with "<password>"
    And I attach the file "<file>" to "register_profileImage"
    And I press "<button>"
    Then I should be on "<path>"
    And I should see "<message>"
    Examples:
      | register_path       | path | title             | username | email          | password   | file     | button            | message                                                                                     |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /en/register        | /en/ | Create an account | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Create an account | The account has been created, an email to validate this creation has been sent to you.      |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |
      | /fr/enregistrement  | /fr/ | Créer un compte   | Toto     | toto@gmail.com | Ie1FDLDLMR | test.png | Créer un compte   | Le compte a bien été créé, un email permettant de valider cette création vous a été envoyé. |