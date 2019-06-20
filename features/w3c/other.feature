Feature:
  Controle W3C de la page d'aide


  Scenario: Sur la page d'aide, j'ai aucune erreur HTML
    When I am on "/fr/service"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors