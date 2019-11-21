import 'icheck';

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
        this.form.querySelectorAll('input').forEach((inputElement: HTMLInputElement) => {
            switch (inputElement.type.toLowerCase()) {
                case "text":
                case "password":
                case "textarea":
                case "hidden":
                    inputElement.setAttribute('value', '');
                    break;
                case "radio":
                case "checkbox":
                    $(inputElement).iCheck('uncheck');
                    break;
                default:
                    break;
            }
        });

        this.form.querySelectorAll('select').forEach((selectElement: HTMLSelectElement) => {
            if (selectElement.options.item(0).value === 'general') {
                selectElement.selectedOptions.item(0).removeAttribute('selected');
            } else {
                $(selectElement).val(null).trigger('change');
            }

        });
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
        let nextId = partsId[0]+'-'+partsId[1]+'-'+(Number(partsId[2]) + 6);
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
        if (this.targetKeywordField.value == '') {
            this.targetKeywordField.value = this.originKeywordField.value;
            this.targetTypeField.value = this.originTypeField.value;
        }
    }
}
