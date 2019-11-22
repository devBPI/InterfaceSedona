import 'select2';

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
        }).on('select2-opening select2-close', (e) => {
            let generatedElement = document.querySelector('input.select2-input') as HTMLInputElement;
            if (generatedElement) {
                generatedElement.setAttribute('aria-expanded', e.type == 'select2-opening'?'true':'false');
            }
        });

        setTimeout(() => {
            let generatedListId = 'select2-adv-search-langage-results';
            let generatedList = document.querySelector('ul.select2-choices') as HTMLUListElement;
            if (generatedList) {
                generatedList.setAttribute('id', generatedListId);
            }

            let generatedElement = document.querySelector('input.select2-input') as HTMLInputElement;
            if (generatedElement) {
                generatedElement.setAttribute('role', 'listbox');
                generatedElement.setAttribute('aria-controls', generatedListId);
                generatedElement.setAttribute('aria-expanded', 'false');
            }
        }, 0);
    }

}
