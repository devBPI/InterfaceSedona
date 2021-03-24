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


    constructor(private element: HTMLInputElement) {
        this.container = this.element.querySelector('#resume-container');
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
