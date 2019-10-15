import Autocomplete from './autocomplete';

export class CollectionRow {
    private limit: number;
    private count: number;
    private adder: HTMLElement;
    private target: HTMLElement;
    private prototype: string;

    constructor(private element: HTMLInputElement) {
        this.limit = 3;
        this.adder = this.element;
        this.target = document.querySelector(this.element.dataset['target']);
        this.prototype = this.target.dataset['prototype'];
        this.count = this.target.childElementCount;

        this.initListener();
    }


    private initListener() {
        this.adder.addEventListener('click', () => this.addRow());
    }

    addRow(): void {
        if (this.checkLimitChildren()) {
            alert('Limite atteinte : ' + this.limit);
        }

        let newRow = this.getNewRowNode();
        this.target.appendChild(newRow);
        this.addEventListenerToRow(newRow);

        this.count++;
        this.disableAdder();
    }

    private getNewRowNode(): HTMLElement {
        let row = document.createElement('div');
        row.classList.add('search-keyword__group');
        row.classList.add('row');
        row.innerHTML = this.prototype.replace(/__index__/g, this.count.toString());

        return row;
    }

    private addEventListenerToRow(row: HTMLElement): void {
        new Autocomplete(row.querySelector('[data-toggle="autocomplete"]'));
        row.querySelector('button').addEventListener('click', () => this.removeRow(row));
    }

    private removeRow(row: HTMLElement): void {
        row.remove();
        this.count--;

        this.disableAdder();
    }

    private checkLimitChildren(): boolean {
        return this.count >= this.limit;
    }

    private disableAdder(): void {
        if (this.checkLimitChildren()) {
            this.adder.setAttribute('disabled', 'disabled');
        } else {
            this.adder.removeAttribute('disbaled');
        }
    }
}
