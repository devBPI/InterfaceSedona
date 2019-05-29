Feature:
  Controle W3C des pages liés à l'utilisateur


  Scenario: Sur la page ma sélection, j'ai aucune erreur HTML
    When I am on "/fr/donnees-personnelles"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal de suggestion, j'ai aucune erreur HTML
    When I am on "/fr/suggestion"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal d'envois à un ami, j'ai aucune erreur HTML
    When I am on "/fr/envoyer-a-un-ami"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal de création d'une liste, j'ai aucune erreur HTML
    When I am on "/fr/list/cree"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal de modification d'une liste, j'ai aucune erreur HTML
    When I am on "/fr/list/modifier"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors


  Scenario: Sur la modal de suppression d'une liste, j'ai aucune erreur HTML
    When I am on "/fr/list/supprimer"
    Then the response status code should be 200

    When I check source code on W3C validation service
    Then I should see 0 W3C validation errors