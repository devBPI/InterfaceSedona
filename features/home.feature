# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  Controle de la Home


  Scenario: Je peux voir la page d'aceuille
    When I am on "/fr"
    Then the response status code should be 200
    And I should see "Accueil - Catalogue de la Bpi"
    And I should see "Découvrir les parcours"

    And I should not see a "button#modal-list-add-button" element
    And I should see a "p#modal-list-add-button" element

    And I should not see a "button#modal-print-button" element
    And I should see a "p#modal-print-button" element

    And I should not see a "button#modal-send-by-mail-button" element
    And I should see a "p#modal-send-by-mail-button" element

    And I should not see a "button#modal-report-button" element
    And I should see a "p#modal-report-button" element


  Scenario: Je peux voir  la page d'accueil thématique Autoformation
    When I am on "/fr/autoformation"
    Then the response status code should be 200
    And I should see "Autoformation"
    And I should see "Les essentiels"


  Scenario: Je peux voir  la page d'accueil thématique Presse
    When I am on "/fr/actualites-revues"
    Then the response status code should be 200
    And I should see "Actualités et revues"
    And I should see "Les essentiels"


  Scenario: Je peux voir  la page d'accueil thématique Cinéma
    When I am on "/fr/cinema"
    Then the response status code should be 200
    And I should see "Cinéma"
    And I should see "Les essentiels"