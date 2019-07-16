Feature:
  Controle W3C des pages liés à l'utilisateur


  Scenario: Sur la page ma sélection, j'ai aucune erreur HTML
    When I am on "/fr/donnees-personnelles"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page de connexion, j'ai aucune erreur HTML
    When I am on "/fr/login"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors