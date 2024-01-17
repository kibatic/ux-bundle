import { Controller } from '@hotwired/stimulus'
import { Modal } from 'bootstrap'

export default class extends Controller {
    static values = {
        autoOpen: Boolean,
        closeOnSuccess: Boolean,
        stayOnSuccess: Boolean,
        relatedTurboFrames: Array,
        hideBackdrop: Boolean,
    }

    static targets = ['turboFrame']

    initialize() {
        // Astuce pour pouvoir à la fois accéder au context de l'instance depuis la méthode (grâce à bind) et pouvoir
        // supprimer l'event listener depuis la méthode disconnect (grâce à l'assignation dans une variable).
        this.turbo_onFrameRender = this._turbo_onFrameRender.bind(this);
        this.turbo_onSubmitEnd = this._turbo_onSubmitEnd.bind(this);
        // this.bsOffcanvas_OnHide = this._bsOffcanvas_OnHide.bind(this);
    }

    connect() {
        this.element.addEventListener('turbo:frame-render', this.turbo_onFrameRender)
        this.element.addEventListener('turbo:submit-end', this.turbo_onSubmitEnd)

        // Remove instantly the modal backdrop before turbo save the page snapshot into its cache.
        document.addEventListener('turbo:before-cache', () => {
            this.element.classList.remove('fade');
            let modal = this.getModal()
            modal._backdrop._config.isAnimated = false;
            modal.hide();
            modal.dispose();
        })

        console.log('Modal -> autoOpen: ' + this.autoOpenValue)

        if (this.autoOpenValue) {
            this.open()
        }
    }

    disconnect() {
        this.element.removeEventListener('turbo:frame-render', this.turbo_onFrameRender)
        this.element.removeEventListener('turbo:submit-end', this.turbo_onSubmitEnd)
    }

    getModal() {
        return Modal.getOrCreateInstance(this.element, { backdrop: !this.hideBackdropValue })
    }

    async open() {
        this.getModal().show()
    }

    async close() {
        this.getModal().hide()
    }

    _turbo_onFrameRender(event) {
        if (this.stayOnSuccessValue) {
            this.element.querySelectorAll('form').forEach(form => {
                form.dataset.turboOnSuccess = 'stay'
            })
        }
    }

    _turbo_onSubmitEnd(event) {
        if (!event.detail.success) {
            return
        }

        if (event.target.dataset.ignoredByModal) {
            return;
        }

        // On ferme automatiquement la modal si l'option closeOnSuccess est activée.
        if (this.closeOnSuccessValue) {
            this.close()

            // On rafraichit les éventuelles turbo-frames liées à la modal.
            for (let relatedTurboFrameId of this.relatedTurboFramesValue) {
                let turboFrames = document.querySelectorAll(relatedTurboFrameId)

                turboFrames.forEach(turboFrame => {
                    let turboFrameSrc = turboFrame.getAttribute('src')

                    // On cherche le src de la première turbo-frame parente qui a un src pour s'en servir de source
                    // sur la frame courante si celle-ci n'en a pas.
                    let parentTurboFrame = turboFrame.parentElement.closest('turbo-frame[src]');
                    let parentTurboFrameSrc = parentTurboFrame && parentTurboFrame.getAttribute('src')

                    turboFrameSrc = turboFrameSrc ?? parentTurboFrameSrc ?? document.location

                    console.log('Modal -> onSuccess -> Close -> refresh frame : ' + relatedTurboFrameId + ' (src=' + turboFrameSrc + ')', turboFrame, parentTurboFrame, turboFrameSrc)

                    turboFrame.setAttribute('src', '')
                    turboFrame.setAttribute('src', turboFrameSrc)
                })
            }
        }
    }
}
