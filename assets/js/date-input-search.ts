import Slider from 'bootstrap-slider';

export class DateSlider {
    private slider: Slider;
    private updateActionTimer: number;
    private textInputList: NodeListOf<HTMLInputElement>;
    private checkboxInputList: NodeListOf<HTMLInputElement>;

    constructor(element: HTMLInputElement) {
        if (element) {
            this.slider = new Slider('#' + element.id, {
                handle: 'square'
            });

            this.slider.on('change', (event) => {
                this.changeInputValues(event.newValue);
            });

            $(element).on('slide', (event: any) => {
                this.changeInputValues(event.value);
            });

            this.initializeInput();
        }
    }

    private initListener() {
        this.textInputList.forEach((item: HTMLInputElement, index: number) => {
            item.addEventListener('keyup', () => {
                this.updateSlider(index, Number(item.value));
            });
            item.addEventListener('change', () => {
                this.updateSlider(index, Number(item.value));
            });
        })
    }

    private updateSlider(index: number, currentValue: number) {
        window.clearTimeout(this.updateActionTimer);

        this.updateActionTimer = window.setTimeout(() => {
            let sliderValues = this.slider.getValue();
            let sliderMinValue = this.slider.getAttribute('min');
            let sliderMaxValue = this.slider.getAttribute('max');
            if (currentValue >= sliderMinValue && currentValue <= sliderMaxValue) {
                sliderValues[index] = currentValue;

                this.slider.setValue(sliderValues);
                this.changeInputValues(sliderValues);
            } else {
                console.log('Please input date between '+sliderMinValue+' and '+sliderMaxValue);
            }
        }, 300);
    }

    private initializeInput() {
        this.textInputList = document.querySelectorAll('input.date-picker');
        this.checkboxInputList = document.querySelectorAll('input.date-checkbox');

        this.initListener();
    }

    private changeInputValues(currentValues: Array<string>) {
        window.clearTimeout(this.updateActionTimer);

        this.updateActionTimer = window.setTimeout(() => {
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
        }, 300);
    }

}

export class DatePeriod {
    private dateDiv: HTMLDivElement;
    private periodDiv: HTMLDivElement;

    constructor(radios: NodeListOf<HTMLInputElement>) {
        this.dateDiv = document.querySelector('#js-date-div');
        this.periodDiv = document.querySelector('#js-period-div');

        radios.forEach((radioElement: HTMLInputElement) => {
            if (radioElement.value === 'Periode') {
                this.togglePeriod(radioElement.checked, false);
            }
            radioElement.addEventListener('click', (event) => {
                let target = event.target as HTMLInputElement;
                this.togglePeriod(target.value === 'Periode', true);
            });
        })
    }

    private hidePeriod(): void {
        this.dateDiv.classList.remove('d-none');
        this.periodDiv.classList.add('d-none');
    }

    private showPeriod(): void {
        this.dateDiv.classList.add('d-none');
        this.periodDiv.classList.remove('d-none');
    }

    private clearInput(containerDiv: HTMLDivElement) {
        containerDiv.querySelectorAll('input').forEach((input: HTMLInputElement) => {
            input.value = '';
        })
    }

    private togglePeriod(periodChecked: boolean, clear: boolean) {
        if (periodChecked) {
            this.showPeriod();
            if (clear) {
                this.clearInput(this.dateDiv);
            }
        } else {
            this.hidePeriod();
            if (clear) {
                this.clearInput(this.periodDiv);
            }
        }
    }
}
