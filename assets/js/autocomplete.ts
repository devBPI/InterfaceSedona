export default class Autocomplete {
    private autocompleteRequest;
    private url: string;
    private mode: string;
    private target: HTMLElement;
    private type: HTMLSelectElement;
    private mapping = {
        'general': 'general',
        'title': 'titre',
        'collection': 'collection',
        'author': 'auteur',
        'realisator': 'realisateur',
        'subject': 'sujet',
        'theme': 'theme',
        'editor': 'editeur',
        'publicationDate': 'date-publication',
        'isbnIssnNumcommercial': 'isbn-issn-numcommercial',
        'indiceCote': 'indice-cote'
    }


    constructor(private element: HTMLInputElement) {
        this.url = this.element.dataset['url'];
        this.mode = this.element.dataset['mode'];

        this.target = document.querySelector(this.element.dataset['target']);
        this.type = document.querySelector(this.element.dataset['type']);

        this.initListener();
    }


    private initListener() {
        this.type.addEventListener('focus', () => this.hideAutocompleteDiv(false));
        this.element.addEventListener('keyup', (event: KeyboardEvent) => this.onKeyUp(event));
        this.type.addEventListener('change', () => this.updateAutocompletion());
        this.target.addEventListener('keyup', (event) => this.manageFocus(event, this.mode !== 'link'));
        document.addEventListener('click', (event) => this.onClickOut(event));
    }

    onKeyUp(event: KeyboardEvent): void {
        if (event.keyCode == 27) {
            this.hideAutocompleteDiv();
            return;
        }

        if (event.keyCode == 40 && this.target.querySelector('a')) {
            this.target.querySelector('a').focus();
            return;
        }

        this.updateAutocompletion();
    }

    updateAutocompletion(): void {
        window.clearTimeout(this.autocompleteRequest);
        this.hideAutocompleteDiv();

        const {value} = this.element;
        if (value.length >= 3) {
            this.autocompleteRequest = window.setTimeout(() => {
                const params = $.param({word: value, type: this.type.value, mode: this.mode});
                fetch(`${this.url}?${params}`)
                    .then(result => this.onAutocompleteFetched(result));
            }, 300);
        }
    }

    private async onAutocompleteFetched(result: Response) {
        let resultList = (await result.json()).html;

        if (resultList == '') {
            this.hideAutocompleteDiv();
            return;
        }

        this.target.innerHTML = resultList;
        this.showAutocompleteDiv();

        if (this.mode !== 'link') {
            this.target.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', (event) => this.setInputValueAndHideList(event))
            });
        }
    }

    onClickOut(event: MouseEvent): void {
        if (!this.target.contains(event.target as HTMLElement)) {
            this.hideAutocompleteDiv();
        }
    }

    setInputValueAndHideList(event: MouseEvent) {
        event.stopPropagation();
        event.preventDefault();

        this.element.value = (event.target as HTMLElement).innerText;
        this.hideAutocompleteDiv();
    }

    private showAutocompleteDiv() {
        this.element.setAttribute('aria-expanded', 'true');
        if (this.target.classList.contains('sr-only')) {
            this.target.classList.remove('sr-only');
        }
    }
    private hideAutocompleteDiv(autofocus = true) {
        this.element.setAttribute('aria-expanded', 'false');
        if (!this.target.classList.contains('sr-only')) {
            this.target.innerHTML = '';
            this.target.classList.add('sr-only');
            if (autofocus) {
                this.element.focus();
            }
        }
    }

    private manageFocus(event, arrowNavig: boolean) {
        if (event.keyCode == 27) {
            this.hideAutocompleteDiv();

            event.stopPropagation();
            event.preventDefault();
        }

        if (arrowNavig) {
            if (event.keyCode == 40) {
                if (event.target.parentElement &&
                    event.target.parentElement.nextElementSibling &&
                    event.target.parentElement.nextElementSibling.firstElementChild) {
                    event.target.parentElement.nextElementSibling.firstElementChild.focus();
                }
            }
            if (event.keyCode == 38) {
                if (event.target.parentElement &&
                    event.target.parentElement.previousElementSibling &&
                    event.target.parentElement.previousElementSibling.firstElementChild) {
                    event.target.parentElement.previousElementSibling.firstElementChild.focus();
                }
            }
        }
    }
}
