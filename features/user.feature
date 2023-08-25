Feature:
  Controle des pages liés à l'utilisateur


  Scenario: Je peux voir  la page mon compte
    When I am on "/fr/compte"
    Then the response status code should be 200
    And I should see "Ma sélection"
    And I should see "Mon historique"

  Scenario: Je peux voir  la page de mon historique de recherche
    When I am on "/fr/historique"
    Then the response status code should be 200
    And I should see "Mon historique"

    And I should not see a "button#modal-list-add-button" element
    And I should see a "p#modal-list-add-button" element

    And I should not see a "button#modal-print-button" element
    And I should see a "p#modal-print-button" element

    And I should not see a "button#modal-send-by-mail-button" element
    And I should see a "p#modal-send-by-mail-button" element

    And I should not see a "button#modal-report-button" element
    And I should see a "p#modal-report-button" element

  Scenario: Je peux voir  la page de connexion
    When I am on "/fr/authentification"
    Then the response status code should be 200

  # ajouter les tests des autre route de la séléaction
  Scenario: Je peux voir  la page ma sélection
    When I am on "/fr/selection"
    Then the response status code should be 200
    And I should see "Ma sélection"

    And I should not see a "button#modal-list-add-button" element
    And I should see a "p#modal-list-add-button" element

    And I should see a "button#modal-print-button[data-toggle=disable-if-no-found-modal][disabled=disabled][data-spy]" element
    And I should not see a "p#modal-print-button" element

    And I should see a "button#modal-send-by-mail-button[data-toggle=disable-if-no-found-modal][disabled=disabled][data-spy]" element
    And I should not see a "p#modal-send-by-mail-button" element

    And I should not see a "button#modal-report-button" element
    And I should see a "p#modal-report-button" element

  # Les ajoutes de notice a la séléction son tester dans les 3 type de notices