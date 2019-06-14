# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  Controle W3C des page de la Home


  Scenario: Sur la page d'accueil, j'ai aucune erreur HTML
    When I am on "/fr"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'accueil thématique Autoformation, j'ai aucune erreur HTML
    When I am on "/fr/accueil/autoformation"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'accueil thématique Presse, j'ai aucune erreur HTML
    When I am on "/fr/accueil/presse"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'accueil thématique Cinéma, j'ai aucune erreur HTML
    When I am on "/fr/accueil/cinema"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors