# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  Controle W3C des page de la Home


  Scenario: Sur la page d'aceuille j'ai aucune erreur HTML
    When I am on "/fr"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'aceuille Tenamtique j'ai aucune erreur HTML
    When I am on "/fr/accueille/auto-formation"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors

