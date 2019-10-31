import 'icheck';
import 'select2';

export default class SearchForm {
    constructor(private form: HTMLFormElement) {
        let resetButton = form.querySelector('button.js-clean') as HTMLButtonElement;
        if (resetButton) {
            this.initListener(resetButton);
        }
    }

    private initListener(resetButton: HTMLButtonElement) {
        resetButton.addEventListener('click', () => {
            this.resetAllInput();
        })
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
}
