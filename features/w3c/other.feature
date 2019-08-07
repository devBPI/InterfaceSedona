Feature:
  Controle W3C de la page d'aide


  Scenario: Sur la page d'aide "La recherche", j'ai aucune erreur HTML
    When I am on "/fr/aide/recherche"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'aide "Exploitation des résultats", j'ai aucune erreur HTML
    When I am on "/fr/aide/resultat"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'aide "Services", j'ai aucune erreur HTML
    When I am on "/fr/aide/service"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page d'aide "Mon compte", j'ai aucune erreur HTML
    When I am on "/fr/aide/compte"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors