import 'select2';
require('../../node_modules/select2/select2_locale_fr.js');

export default class SelectList {
    private placeholder: string;

    constructor(private element: HTMLSelectElement) {
        let placeholder = this.element.dataset['value'];

        $(this.element).select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            formatNoMatches: (term: string) => {
                return 'Aucune langue trouvée pour la saisie '+term;
            }
        })
        .on('select2-opening select2-close', (e) => {
            let generatedElement = document.querySelector('input.select2-input') as HTMLInputElement;
            if (generatedElement) {
                generatedElement.setAttribute('aria-expanded', (e.type == 'select2-opening') ? 'true' : 'false');
                this.updateTotalElement(generatedElement);
            }
        })
        .on('select2-removed', (e) => {
            let generatedElement = document.querySelector('input.select2-input') as HTMLInputElement;
            if (generatedElement) {
                this.updateTotalElement(generatedElement);
            }
        })
        .on('select2-open', function () {
            var object = $(this).data('select2');
            object.select.before(object.liveRegion);
        });

        setTimeout(() => {
            $("[data-toggle=select2]").each((el) => {
                let $this = $(this), 
                    generatedListId = 'select2-adv-' + $this.attr('id'),
                    object = $this.data('select2');

                if (object !== 'undefined') {
                    object.selection.attr('id', generatedListId);
                    object.search
                        .attr('role', 'button')
                        .attr('aria-controls', generatedListId)
                        .attr('aria-expanded', 'false');

                    this.updateTotalElement(object.search);
                }
            });
        }, 0);
    }

    private updateTotalElement(list:any) {
        let choicesList = document.querySelector('ul.select2-choices') as HTMLUListElement;
        if (choicesList) {
            let countChoices = choicesList.children.length - 1;

            list.setAttribute('aria-label', countChoices + ' éléments sélectionnés');
        }
    }
}
