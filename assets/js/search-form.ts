// import InputDecorator from './input-decorator';
import {CollectionRow} from './collection-row';

export class SearchForm {
    resetButton: HTMLButtonElement;
    seeMoreButtonList: NodeListOf<HTMLLinkElement>;
    collectionRowList: Array<CollectionRow>;

    constructor(private form: HTMLFormElement) {
        this.resetButton = form.querySelector('button.js-clean') as HTMLButtonElement;
        this.seeMoreButtonList = form.querySelectorAll('.js-see-more');

        this.collectionRowList = new Array;
        let collectionAdder = form.querySelectorAll('[data-toggle="collection-add"]').forEach(
            (adder: HTMLInputElement) => this.collectionRowList.push(new CollectionRow(adder))
        );

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
                    inputElement.value='';
                    inputElement.setAttribute('value', '');
                    break;
                case "checkbox":
                    // let inputDecorator = new InputDecorator($(inputElement));
                    // inputDecorator.uncheck();
                    // break;
                    inputElement.checked=false;
                case "radio":
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

            //selectedOptions[0].setAttribute('selected', 'selected');

            $(selectElement).val(null).trigger('change');
            if(selectElement.id=="adv-search-choice-0")
                selectElement.selectedIndex = 0;
        }
    }

    private clearKeywordRows(container: HTMLFormElement) {
        this.collectionRowList.forEach((collectionRow) => {
            collectionRow.clear();
        })
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
