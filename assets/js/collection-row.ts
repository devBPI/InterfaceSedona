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

        this.target.appendChild(this.getNewRowNode());
        this.count++;

        this.disableAdder();
    }

    private getNewRowNode(): HTMLElement {
        let row = document.createElement('div');
        row.classList.add('search-keyword__group');
        row.classList.add('row');
        row.innerHTML = this.prototype.replace(/__index__/g, this.count.toString());

        let input = $(row).find('[data-toggle="autocomplete"]').get(0);
        console.log(input);
        new Autocomplete(input);
        // for(row.getElementsByTagName('input')forEach(result => {
        //     new Autocomplete(result);
        // })
        // input.addEventListener('keyu', () => this.removeRow(row));

        let remover = $(row).find('button').get(0);
        remover.addEventListener('click', () => this.removeRow(row));

        return row;
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

// .on('click', '[data-toggle="collection-add"]', function () {
//     var $this = $(this),
//         $prototype = $this.data('target') != undefined ? $($this.data('target')) : $this,
//         newWidget = $prototype.data('prototype'),
//         widgetCount = $prototype.data('count');
//
//     if ($this.data('limit') != undefined && $prototype.children().length >= $this.data('limit')) {
//         alert('Limite atteinte : '+$this.data('limit'));
//         return false;
//     }
//
//     var protoname = new RegExp($prototype.data('prototype-name') != undefined ? $prototype.data('prototype-name') : '__index__',"g");
//
//     // remplace les "__id__" utilisés dans l'id et le nom du prototype
//     // par un nombre unique pour chaque email
//     // le nom de l'attribut final ressemblera à name="contact[emails][2]"
//     newWidget = newWidget.replace(protoname, widgetCount);
//     widgetCount++;
//
//     // créer une nouvelle liste d'éléments et l'ajoute à notre liste
//     $prototype
//         .append(newWidget)
//         .data('count', widgetCount);
//
//     if ($this.data('placement') && $this.data('placement') == "new")
//         $(document.body).scrollTop($prototype.children().last().offset().top);
//
//     if ($this.data('modal'))
//         $('#'+$this.data('modal')).modal('show');
//
//     if ($this.data('limit') != undefined && $prototype.children().length >= $this.data('limit')) {
//         $this.attr('disabled', true);
//     }
//
//     return false;
// })
//     .on('click', '[data-toggle="remove-element"]', function (e) {
//         var $this = $(this),
//             $target = $this.data('parent') != undefined ? $this.parents($this.data('parent')).first()  : $this,
//             $parent = $target.parents(':first');
//         if($this.hasClass('btn-confirm')) {
//             $('#confirmation-modal #confirmation-modal-confirm').one('click',function(e){
//                 e.preventDefault();
//                 $target.remove();
//                 $('#confirmation-modal').modal('hide');
//                 $parent.trigger('change');
//             });
//             e.preventDefault();
//             return false;
//         }
//         $target.remove();
//         $parent.trigger('change');
//         $('[data-toggle="collection-add"]').attr('disabled', false);
//     })
