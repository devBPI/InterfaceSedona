Feature:
  Controle de la page d'aide


  Scenario: Je peux voir  la page d'aide "La recherche"
    When I am on "/fr/aide/recherche"
    Then the response status code should be 200


  Scenario: Je peux voir  la page d'aide "Exploitation des résultats"
    When I am on "/fr/aide/exploitationresultats"
    Then the response status code should be 200


  Scenario: Je peux voir  la page d'aide "Services"
    When I am on "/fr/aide/services"
    Then the response status code should be 200


  Scenario: Je peux voir  la page d'aide "Mon compte"
    When I am on "/fr/aide/compte"
    Then the response status code should be 200