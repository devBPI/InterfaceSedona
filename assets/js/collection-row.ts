import Autocomplete from './autocomplete';

export class CollectionRow {
    private limit: number;
    private count: number;
    private index: number;
    private adder: HTMLElement;
    private target: HTMLElement;
    private prototype: string;

    constructor(private element: HTMLInputElement) {
        this.adder = this.element;
        this.limit = parseInt(this.element.dataset['limit']);
        this.target = document.querySelector(this.element.dataset['target']);
        this.prototype = this.target.dataset['prototype'];
        this.count = this.target.childElementCount;
        this.index = this.count;
        this.hideAdder();

        this.initListener();
    }


    private initListener() {
        this.adder.addEventListener('click', () => this.addRow());
        this.target.querySelectorAll('.search-keyword__group').forEach((row: HTMLDivElement) => {
            let removeButton = row.querySelector('button.js-remove-row') as HTMLButtonElement;
            if (removeButton) {
                removeButton.addEventListener('click', () => this.removeRow(row));
            }
        });
    }

    addRow(): void {
        if (this.checkLimitChildren()) {
            alert('Limite atteinte : ' + this.limit);
        }

        let newRow = this.getNewRowNode();
        this.target.appendChild(newRow);
        this.addEventListenerToRow(newRow);

        this.count++;
        this.index++;
        this.hideAdder();

        this.setFocusOnInputOfRow(newRow);
    }

    private getNewRowNode(): HTMLElement {
        let row = document.createElement('div');
        row.classList.add('search-keyword__group');
        row.classList.add('row');
        row.innerHTML = this.prototype.replace(/__index__/g, this.index.toString());

        return row;
    }

    private addEventListenerToRow(row: HTMLElement): void {
        new Autocomplete(row.querySelector('[data-toggle="autocomplete"]'));
        row.querySelector('button.js-remove-row').addEventListener('click', () => this.removeRow(row));
    }

    private removeRow(row: HTMLElement): void {
        let parentElement = row.parentElement;

        row.remove();
        this.count--;

        this.hideAdder();

        let prevRow = parentElement.lastElementChild as HTMLElement;
        this.setFocusOnInputOfRow(prevRow);
    }

    private checkLimitChildren(): boolean {
        return this.count >= this.limit;
    }

    private hideAdder(): void {
        if (this.checkLimitChildren()) {
            this.adder.classList.add('d-none');
        } else {
            this.adder.classList.remove('d-none');
        }
    }

    private setFocusOnInputOfRow(row: HTMLElement): void {
        let input = row.querySelector('input') as HTMLInputElement;
        if (input) {
            input.focus();
        }
    }

    clear() {
        this.target.querySelectorAll('.search-keyword__group').forEach(
            (row: HTMLDivElement, index: number) => {
                if (index > 0) {
                    row.remove();
                }
            });

        this.count = 1;
        this.hideAdder();
    }
}
