import $ from 'jquery';

class Loading {
    constructor(selector) {
        this.element = $(selector);
        this.element.hide(); // Initially hide the loading indicator
    }

    show() {
        this.element.show();
    }

    hide() {
        this.element.hide();
    }
}

export default Loading;