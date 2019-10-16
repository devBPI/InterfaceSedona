export default class Autocomplete {
    private autocompleteRequest;
    private url: string;
    private target: HTMLElement;
    private type: HTMLSelectElement;

    constructor(private element: HTMLInputElement) {
        this.url = this.element.dataset['url'];
        this.target = document.querySelector(this.element.dataset['target']);
        this.type = document.querySelector(this.element.dataset['type']);

        this.initListener();
    }


    private initListener() {
        this.element.addEventListener('keyup', () => this.onKeyUp());
        this.type.addEventListener('change', () => this.onKeyUp());
        document.addEventListener('click', (event) => this.onClickOut(event))
    }

    onKeyUp(): void {
        window.clearTimeout(this.autocompleteRequest);
        this.target.classList.add('d-none');

        const {value} = this.element;
        if (value.length >= 3) {
            this.autocompleteRequest = window.setTimeout(() => {
                const params = $.param({word: value, type: this.type.value});
                fetch(`${this.url}?${params}`)
                    .then(result => this.onAutocompleteFetched(result));

            }, 300);
        }
    }

    async onAutocompleteFetched(result: Response) {
        this.target.classList.remove('d-none');
        this.target.innerHTML = (await result.json()).html;
        // this.target.querySelectorAll('p').forEach(function (link, ) {
        //     link.addEventListener('click', () => this.setInput(event))
        // })
    }

    onClickOut(event: MouseEvent): void {
        if (!this.target.contains(event.target as HTMLElement)) {
            this.target.classList.add('d-none');
        }
    }

    setInput(event: MouseEvent) {
        event.stopPropagation();

        this.element.value = (event.target as HTMLAnchorElement).innerText;
    }
}
