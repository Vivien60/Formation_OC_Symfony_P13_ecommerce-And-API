import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('Hello from your controller:', this.element);
    }

    emptyCart(event) {
        console.log('emptyCart');
        event.preventDefault();
        const form = document.querySelector(this.element.dataset.target);
        console.log(event);
        console.log(this.element.dataset.target);
        console.log(form);
        form.requestSubmit();
    }
}
