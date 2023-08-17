export class Printer {
    constructor() {
        this.initListener();
    }

    private initListener() {
        document.addEventListener('click', (event: MouseEvent) => {
            let target = event.target;
            if(target instanceof HTMLElement && (target.classList.contains('js-export-form') ||  target.classList.contains('js-print-action') ||  target.classList.contains('js-print-selection-action') ||  target.classList.contains('js-selection-print-action') )) {
                this.onClick();
            }


        })
    }

    private onClick(): void {
        let permalinkAuthority = $('.js-authority:checked');
        let permalinkNotice = $('.js-notice:checked');
        let permalinkIndice = $('.js-indicecdu:checked');
        let notice = [];
        let authority = [];
        let indice= [];
        permalinkNotice.each(function () {
            if ($(this).data('notice')){
                notice.push($(this).data('notice'));
            }
        });
        permalinkAuthority.each(function () {
            if ($(this).data('authority')){
                authority.push($(this).data('authority'));
            }
        });

        permalinkIndice.each(function () {
            if ($(this).data('indicecdu')){
                indice.push($(this).data('indicecdu'));
            }
        });


        $('.js-print-notices').val(JSON.stringify(notice));
        $('.js-print-authorities').val(JSON.stringify(authority));
        $('.js-print-indices').val(JSON.stringify(indice));
    }
}

export class CopyToClipboard {
    constructor(private element: HTMLLinkElement) {
        this.initListener();
    }

    private initListener() {
        this.element.addEventListener('click', () => this.onClick());
    }

    private onClick(): void {
        let field = document.querySelector('.js-url-to-copy') as HTMLInputElement;
        if (field && field.value !== '') {
            field.select();
            document.execCommand("copy");
        }
    }
}