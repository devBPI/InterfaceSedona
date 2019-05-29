Feature:
  Controle W3C des pages liés à la recherche


  Scenario: Sur la page résultat de recherche, j'ai aucune erreur HTML
    When I am on "/fr/recherche"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal recherche avancée, j'ai aucune erreur HTML
    When I am on "/fr/recherche-avance"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors