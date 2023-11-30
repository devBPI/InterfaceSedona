Feature:
  Controle des page d'erreur

  Scenario: Je peux voir  la page d'erreur 404
    When I am on "/_error/404"
    Then the response status code should be 200

  Scenario: Je peux voir  la page d'erreur 500
    When I am on "/_error/500"
    Then the response status code should be 200