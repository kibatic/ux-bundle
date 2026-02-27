import { Controller } from '@hotwired/stimulus'
import Swal from 'sweetalert2'

export default class extends Controller {
    static values = {
        title: { type: String, default: "Êtes-vous sûr ?" },
        text: { type: String, default: "Vous ne pourrez pas revenir en arrière !" },
        confirmBtn: { type: String, default: 'Oui' },
        cancelBtn: { type: String, default: 'Non' },
    }

    alreadySubmitted = false
    alreadyClicked = false
    boundEvent = null
    boundHandler = null

    connect() {
        this.boundHandler = this.handle.bind(this)
        this.boundEvent = this.getEventType()

        // S'il n'y a pas d'appel explicite (ce qui est le cas le plus courant) on rajoute l'event listener automatiquement.
        if (!this.element.getAttribute('data-action')?.includes('confirm#handle')) {
            this.element.addEventListener(this.boundEvent, this.boundHandler)
        }
    }

    disconnect() {
        if (this.boundEvent && this.boundHandler) {
            this.element.removeEventListener(this.boundEvent, this.boundHandler)
        }
    }

    getEventType() {
        return this.element.tagName === 'FORM' ? 'submit' : 'click'
    }

    getConfirmOptions() {
        return  {
            title: this.titleValue,
            text: this.textValue,
            html: this.textValue,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: this.cancelBtnValue,
            confirmButtonText: this.confirmBtnValue,
            inputAutoFocus: false,
        }
    }

    handle(event) {
        if (event instanceof SubmitEvent) {
            if (this.alreadySubmitted) {
                return
            }
        } else {
            if (this.alreadyClicked) {
                return
            }
        }

        event.preventDefault()
        event.stopImmediatePropagation()

        let thenCallback

        if (event instanceof SubmitEvent) {
            this.alreadySubmitted = true
            thenCallback = this.confirmAfterSubmit.bind(this)
        } else {
            this.alreadyClicked = true
            thenCallback = this.confirmAfterClick.bind(this)
        }

        Swal.fire(this.getConfirmOptions())
            .then(thenCallback.bind(this))

        return false
    }

    confirmAfterSubmit(result) {
        if (result.isConfirmed) {
            this.element.requestSubmit()
        }

        this.alreadySubmitted = false
    }

    confirmAfterClick(result) {
        if (result.isConfirmed) {
            this.element.click()
        }

        this.alreadyClicked = false
    }
}
