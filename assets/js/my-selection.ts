import Routing from '../../assets/js/jsrouting.min.js';

export class SelectionList {
    constructor() {}

    updateListCount(count: number) {
        $('#countSelectionList span').html('(' + count + ')');
    }

    updateDocumentCount(count: number) {
        $('#countSelectionDocument span').html('('+ count +')');
    }

    onClickMyList(id: number) {

        var element = <HTMLInputElement> document.getElementById('select-liste-'+id);
        var items =  document.getElementsByClassName('js-my_selection_'+id);
        if (element.checked){
            for (var i=0; i < items.length; i++) {
                var  item = <HTMLInputElement> document.getElementById( items[i].id);
                item.checked = true;
            }
        }else{
            for (var i=0; i < items.length; i++) {
                var  item = <HTMLInputElement> document.getElementById( items[i].id);
                item.checked = false;
            }
        }
    }
}


export class SelectionAdder {
    private container: HTMLDivElement;
    private psronly: HTMLDivElement;
    private titles: Array<String>;


    constructor(private element: HTMLInputElement) {
        this.container = this.element.querySelector('#resume-container');
        this.psronly = this.element.querySelector('#selected-list-container');
        this.titles = [];
    }

    hideContainer() {
        $(this.container.parentNode).hide();
    }

    displaySelectedResume(objects) {
        this.container.innerHTML = '';

        for (let [key, value] of objects) {
            let container = $(value).parents('.js-list-result-item');
            if (container.length > 0) {
                this.cloneSelectedItemInContainer(container.get(0), key);
            }
        }

        this.psronly.innerHTML = this.psronly.dataset.textBegin+' '+this.titles.join(', ').trim()+' '+this.psronly.dataset.textEnd;
    }

    displayCurrentItemResume() {
        fetch(Routing.generate('recorded'))
    }

    cloneSelectedItemInContainer(card: HTMLElement, index: string) {
        let clone = card.cloneNode(true) as HTMLElement;

        if (index !== undefined) {
            clone = this.replaceChildrenNameByIndex(clone, index);
        }

        this.container.append(clone);

        var title = $(clone).find('.js-add-title').text().trim();
        this.titles[index] = title;
    }

    replaceChildrenNameByIndex(container: HTMLElement, index: string) {
        container.querySelectorAll('input').forEach(function(input: HTMLInputElement) {
            input.name = input.name.replace(/__item__/gi, index);
        })

        return container;
    }

    cleanContainer() {
        while (this.container.firstChild) this.container.removeChild(this.container.firstChild);
    }
}
