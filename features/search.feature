Feature:
  Controle des pages liés à la recherche


  Scenario: Je peux voir  la page résultat de recherche simple
    When I am on "/fr/recherche-simple?mot=daft+punk"
    Then the response status code should be 200

    And I should see a "button#modal-list-add-button[data-toggle=disable-if-no-found-modal][disabled=disabled][data-spy]" element
    And I should not see a "p#modal-list-add-button" element

    And I should see a "button#modal-print-button[data-toggle=disable-if-no-found-modal][disabled=disabled][data-spy]" element
    And I should not see a "p#modal-print-button" element

    And I should see a "button#modal-send-by-mail-button[data-toggle=disable-if-no-found-modal][disabled=disabled][data-spy]" element
    And I should not see a "p#modal-send-by-mail-button" element

    And I should not see a "button#modal-report-button" element
    And I should see a "p#modal-report-button" element

  Scenario: Je peux voir  la page résultat de recherche avancer
    When I am on "/fr/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=daft+punk"
    Then the response status code should be 200

  Scenario: Sur l'autocompletion
    When I am on "/fr/autocompletion?word=Daft%20Punk"
    Then the response status code should be 200

  #Scenario: Je peux ajouter la notice a ma séléction
  # il faudrait pouvoir envoyer les données en POST

  Scenario: Je peux envoyer par mail la notice
    When I am on "/fr/send-by-mail"
    Then the response status code should be 200
    And I should see "Référence du catalague de la Bpi"


    When I fill in the following:
      | send_by_mail[reciever]     | test@test.fr                    |
      | send_by_mail[message]      | Message                         |
      | send_by_mail[shortFormat]  | 0                               |
      | send_by_mail[image]        | 1                               |
      | send_by_mail[notices]      | ["ark:/34201/nptfl0000824798"]  |
      | send_by_mail[authorities]  | ["ark:/34201/aptfl0000505126"]  |
      | send_by_mail[indices]      | ["ark:/34201/iptfl0000650110"]  |
    And I press "Envoyer"
    Then the response status code should be 200
    And I should see "Votre message a été envoyé avec succès."