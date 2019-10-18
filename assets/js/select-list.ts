import 'select2';

export default class SelectList {
    private placeholder: string;

    constructor(private element: HTMLSelectElement) {
        this.placeholder = this.element.dataset['value'],
        $(this.element).select2({
            placeholder: this.placeholder
        });
    }

}
