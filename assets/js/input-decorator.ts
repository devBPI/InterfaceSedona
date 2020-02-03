import 'icheck/icheck.min';

export default class InputDecorator {
    selector: any;
    constructor(private $selector) {
        this.selector = $selector
    }

    decorate() {
        this.selector.iCheck({
            checkboxClass: 'check check--checkbox',
            radioClass: 'check check--radio',
            focusClass: 'focus'
        });
    }

    uncheck() {
        this.selector.iCheck('uncheck');
        this.selector.removeAttr('checked');
    }

    destroy() {
        this.selector.iCheck('destroy');
    }
}
