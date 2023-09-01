export class Printer {
    private readonly idModalPrint :string = '#modal-print';
    private readonly idModalSendByMail :string = '#modal-send-by-mail';
    private readonly idModalCheck :string = '#modal-selection-export';

    constructor() {
        this.initListener();
        this.copyNoticesIdOnHiddenFiled();
    }

    private initListener() :void
    {
        $(this.idModalPrint).on('shown.bs.modal',  (event) => {
            this.copyNoticesIdOnHiddenFiled();
        });

        $(this.idModalSendByMail).on('shown.bs.modal',  (event) => {
            this.copyNoticesIdOnHiddenFiled();
        });

        $(this.idModalCheck).on('shown.bs.modal',  (event) => {
            this.checkBeforeOpenModal(event.target, event.relatedTarget);
        });
    }

    private copyNoticesIdOnHiddenFiled(): void
    {
        let value = JSON.stringify(this.getNoticesIdSelected('notice'));
        document.querySelectorAll('.js-print-notices').forEach((input :HTMLInputElement) => { input.value = value; });

        value = JSON.stringify(this.getNoticesIdSelected('authority'));
        document.querySelectorAll('.js-print-authorities').forEach((input :HTMLInputElement) => { input.value = value; });

        value = JSON.stringify(this.getNoticesIdSelected('indicecdu'));
        document.querySelectorAll('.js-print-indices').forEach((input :HTMLInputElement) => { input.value = value; });
    }

    private getNoticesIdSelected(type :string) :any[]
    {
        let indice= [];
        document.querySelectorAll('.js-'+type+':checked').forEach((input:HTMLInputElement)=>{
            if (type in input.dataset){
                indice.push(input.dataset[type]);
            }
        });
        return indice;
    }

    // Check si des elements de ne la liste ne sont plus dabs le catalogue
    private checkBeforeOpenModal(modal: HTMLElement, button:HTMLElement) :void
    {
        let originalContent = modal.innerHTML;

        fetch( button.dataset.url, {
            method: 'post',
            body:  JSON.stringify({
                'autorities': this.getNoticesIdSelected('authority'),
                'notices': this.getNoticesIdSelected('notice'),
                'indices': this.getNoticesIdSelected('indicecdu')
            })
        })
            .then(httpResponse => {
                    if (httpResponse.status == 204) {
                        this.openModalAfterCheck(button);
                        return;
                    }
                    httpResponse.text().then(html => {
                        modal.innerHTML = html;
                        modal.querySelector('button[type=submit]').addEventListener('click',(event)=>{
                            event.stopPropagation();
                            event.preventDefault();
                            this.openModalAfterCheck(button);
                            modal.innerHTML = originalContent;

                        });
                    });
                }
            )
            .catch(error => {
                console.error(error);
            })
    }

    private openModalAfterCheck( button:HTMLElement) :void
    {
        $(this.idModalCheck).modal('hide');
        console.log(button,button.dataset.action );
        if(button.dataset.action == 'print'){
            $(this.idModalPrint).modal('show');
        } else {
            $(this.idModalSendByMail).modal('show');
        }
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