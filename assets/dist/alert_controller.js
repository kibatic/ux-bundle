import { Controller } from '@hotwired/stimulus'
import Swal from 'sweetalert2'

export default class extends Controller {
    static values = {
        options: Object,
    }

    connect() {
        let ctrlElement = this.element

        Swal.fire({
            didClose: function () {
                ctrlElement.remove()
            },
            ...this.optionsValue,
        })
    }
}