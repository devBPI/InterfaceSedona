Feature:
  Controle W3C des page liée aux notices d'autorite,


  Scenario: Je peux voir  la page de détails d'une notice d'autorite
    When I am on "/fr/autorite/ark:/34201/aptfl0000505126"
    Then the response status code should be 200

    And I should see a "button#modal-list-add-button[data-toggle=modal]:not([disabled=disabled])" element
    And I should not see a "p#modal-list-add-button" element

    And I should see a "button#modal-print-button[data-toggle=modal]:not([disabled=disabled][data-spy])" element
    And I should not see a "p#modal-print-button" element

    And I should see a "button#modal-send-by-mail-button[data-toggle=modal]:not([disabled=disabled][data-spy])" element
    And I should not see a "p#modal-send-by-mail-button" element

    And I should not see a "button#modal-report-button" element
    And I should see a "p#modal-report-button" element

  Scenario:  Je peux imprimer une notice abrégées au format TEXTE
    When I am on "/fr/print/autorite.txt/ark:/34201/aptfl0000505126?debug=on"
    Then the response status code should be 200
    And I should see "Collectivité"

  Scenario:  Je peux imprimer une notice complètes au format TEXTE
    When I am on "/fr/print/autorite.txt/ark:/34201/aptfl0000505126?print-type=print-long&debug=on"
    Then the response status code should be 200
    And I should see "Collectivité"

  Scenario:  Je peux imprimer une notice abrégées au format HTML
    When I am on "/fr/print/autorite.html/ark:/34201/aptfl0000505126"
    Then the response status code should be 200
    And I should see "Collectivité"

  Scenario:  Je peux imprimer une notice complètes au format HTML
    When I am on "/fr/print/autorite.html/ark:/34201/aptfl0000505126?print-type=print-long"
    Then the response status code should be 200
    And I should see "Collectivité"

  Scenario: Je peux ajouter la notice a ma séléction
    When I am on "/fr/selection/list/ajout-documents?document%5B0%5D%5Bid%5D=ark%3A%2F34201%2Faptfl0000505126"
    Then the response status code should be 200
    # IL n'y a pas des sessions ou sauvegarder la séléction....
    #And I should see "Afficher votre sélection temporaire"
    #And I should see "Pour enregistrer votre sélection, connectez-vous à votre compte."

  Scenario: Je peux envoyer par mail la notice
    When I am on "/fr/send-by-mail"
    Then the response status code should be 200
    And I should see "Références du catalogue de la Bpi"


    When I fill in the following:
      | send_by_mail[reciever]     | test@test.fr                    |
      | send_by_mail[message]      | Message                         |
      | send_by_mail[shortFormat]  | 0                               |
      | send_by_mail[image]        | 1                               |
      | send_by_mail[notices]      | []                              |
      | send_by_mail[authorities]  | ["ark:/34201/aptfl0000505126"]  |
      | send_by_mail[indices]      | []                              |
    And I press "Envoyer"
    Then the response status code should be 200
