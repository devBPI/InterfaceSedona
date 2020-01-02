export default class Pagination {

    constructor(private element: HTMLInputElement) {
        this.initListener();
    }

    private initListener() {
        this.element.addEventListener('keyup', (event: KeyboardEvent) => this.onKeyUp(event));
    }

    onKeyUp(event: KeyboardEvent): void {
        if (event.keyCode === 13) {
            let inputPage = parseInt(this.element.value);
            let minPage = parseInt(this.element.getAttribute('min'));
            let maxPage = parseInt(this.element.getAttribute('max'));

            if (this.checkInputPage(inputPage, minPage, maxPage)) {
                let currentUrl = new URL(location.href);
                let queryString = currentUrl.search;
                let urlSearchParams = new URLSearchParams(queryString);
                urlSearchParams.set('page', inputPage.toString());
                currentUrl.search = urlSearchParams.toString();

                window.location.href = currentUrl.toString();
            }

        }
    }

    private checkInputPage(inputPage: number, min: number, max: number): boolean {
        let errorMessage = null;

        if (isNaN(inputPage)) {
            errorMessage = 'Vous devez saisir un nombre entier comme numéro de page';
        } else if (inputPage < min) {
            errorMessage = 'Le numéro de page ne peut être inférieur à ' + min;
        } else if (inputPage > max) {
            errorMessage = 'Le numéro de page ne peut être supérieur au nombre maximum de page ('+ max +').';
        }

        if (errorMessage !== null) {
            alert(errorMessage);
            this.element.value = this.element.dataset['old'];
            return false;
        }

        return true;
    }
}
