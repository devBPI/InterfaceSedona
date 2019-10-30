import Slider from 'bootstrap-slider';

export class DateSlider {
    private transformed: boolean;
    private slider: Slider;
    private textInputList: NodeListOf<HTMLInputElement>;
    private checkboxInputList: NodeListOf<HTMLInputElement>;

    constructor() {
        this.transformed = false;
        this.textInputList = document.querySelectorAll('input.date-picker');
        this.checkboxInputList = document.querySelectorAll('input.date-checkbox');

        this.initListener();
    }

    private initListener() {
        this.textInputList.forEach((item: HTMLInputElement, index: number) => {
            item.addEventListener('keyup', () => {
                this.updateSlider(index, Number(item.value));
            });
        })
    }

    private updateSlider(index: number, currentValue: number) {
        let sliderValues = this.slider.getValue();
        if (currentValue >= sliderValues[0] && currentValue <= sliderValues[1]) {
            sliderValues[index] = currentValue;

            this.slider.setValue(sliderValues);
        }
    }

    createDateSlider(element: HTMLInputElement) {
        if (this.transformed !== true) {
            this.slider = new Slider('#' + element.id, {
                handle: 'square'
            });

            this.slider.on('change', (event) => {
                this.changeInputValues(event.newValue);
            });

            $(element).on('slide', function (event) {
                this.changeInputValues(event.value);
            }.bind(this));

            this.transformed = true;
        }
    }

    private changeInputValues(currentValues: Array<string>) {
        this.textInputList.forEach((inputElement: HTMLInputElement, index: number) => {
            inputElement.value = currentValues[index];
        });

        let min = currentValues[0];
        let max = currentValues[1];
        this.checkboxInputList.forEach((checkboxElement: HTMLInputElement) => {
            if (checkboxElement.value >= min && checkboxElement.value <= max) {
                checkboxElement.setAttribute('checked', 'checked');
            } else {
                checkboxElement.removeAttribute('checked');
            }
        });
    }

}
