import InputDecorator from './input-decorator';
import {CollectionRow} from './collection-row';

export class SearchForm {
    resetButton: HTMLButtonElement;
    seeMoreButtonList: NodeListOf<HTMLLinkElement>;

    constructor(private form: HTMLFormElement) {
        this.resetButton = form.querySelector('button.js-clean') as HTMLButtonElement;
        this.seeMoreButtonList = form.querySelectorAll('.js-see-more');

        this.hideJsHidden();
        this.initListener();
    }

    private hideJsHidden() {
        document.querySelectorAll('.js-hidden').forEach((element: HTMLElement) => {
            element.classList.add('d-none');
        });
    }

    private initListener() {
        if (this.resetButton) {
            this.resetButton.addEventListener('click', () => this.resetAllInput());
        }

        this.seeMoreButtonList.forEach((seeMoreButton: HTMLLinkElement) => {
            seeMoreButton.parentElement.classList.remove('d-none');
            seeMoreButton.addEventListener('click', () => this.displayTargetDiv(seeMoreButton));
        });
    }

    private resetAllInput() {
        this.form.querySelectorAll('select').forEach((selectElement: HTMLSelectElement) => {
            this.clearSelectBox(selectElement);
        });

        this.form.querySelectorAll('input').forEach((inputElement: HTMLInputElement) => {
            switch (inputElement.type.toLowerCase()) {
                case "text":
                case "password":
                case "textarea":
                    inputElement.setAttribute('value', '');
                    break;
                case "radio":
                case "checkbox":
                    let inputDecorator = new InputDecorator($(inputElement));
                    inputDecorator.uncheck();
                    break;
                default:
                    break;
            }
        });

        this.clearKeywordRows(this.form);
    }

    private clearSelectBox(selectElement: HTMLSelectElement) {
        if (selectElement.selectedOptions.length > 0) {
            let selectedOptions = new Array() as Array<HTMLOptionElement>;
            for (let index = 0; index < selectElement.selectedOptions.length; index++) {
                selectedOptions.push(selectElement.selectedOptions.item(index));
            }

            selectedOptions.forEach((option: HTMLOptionElement) => {
                option.removeAttribute('selected');
            })
        }

        $(selectElement).val(null).trigger('change');
    }

    private clearKeywordRows(container: HTMLElement) {
        let keywordsDiv = container.querySelector('.search-keyword') as HTMLDivElement;
        if (keywordsDiv) {
            let rows = keywordsDiv.querySelectorAll('.search-keyword__group');
            if (rows.length > 1) {
                rows.forEach((row: HTMLDivElement, index: number) => {
                    if (index > 0) {
                        row.remove();
                    }
                })
            }
        }
    }

    private displayTargetDiv(seeMoreButton: HTMLLinkElement) {
        let targetId = seeMoreButton.dataset['target'];
        let targetDiv = document.querySelector(targetId) as HTMLDivElement;
        targetDiv.classList.remove('d-none');

        let firstNextInputElement = targetDiv.querySelector('input');
        if (firstNextInputElement) {
            firstNextInputElement.focus();
        }

        let partsId = targetId.split('-');
        let nextId = partsId[0]+'-'+partsId[1]+'-'+partsId[2]+'-'+(Number(partsId[3]) + 6);
        let nextDiv = document.querySelector(nextId) as HTMLDivElement;

        if (nextDiv == null) {
            seeMoreButton.parentElement.remove();
        } else {
            seeMoreButton.setAttribute('data-target', nextId);
            seeMoreButton.setAttribute('aria-controls', nextId.slice(1));
        }
    }

}

export class CopyKeyword {
    originKeywordField: HTMLInputElement;
    originTypeField: HTMLSelectElement;
    targetKeywordField: HTMLInputElement;
    targetTypeField: HTMLSelectElement;

    constructor() {
        this.originKeywordField = document.querySelector('#search-input');
        this.originTypeField = document.querySelector('#search-words');

        this.targetKeywordField = document.querySelector('#adv-search-txt-0');
        this.targetTypeField = document.querySelector('#adv-search-choice-0');
    }

    copyKeywordValue() {
        if (this.targetKeywordField && this.targetKeywordField.value == '') {
            this.targetKeywordField.value = this.originKeywordField.value;
            this.targetTypeField.value = this.originTypeField.value;
        }
    }
}
