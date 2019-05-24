Feature:
  Controle W3C des page liée aux notices


  Scenario: Sur la page notice bibliographique, j'ai aucune erreur HTML
    When I am on "/fr/notice-bibliographique"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la page notice autorite, j'ai aucune erreur HTML
    When I am on "/fr/notice-autorite"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors
