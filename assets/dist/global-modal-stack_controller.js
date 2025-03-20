import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['modalModel']

    connect() {
        // Si on change de page alors qu'une modal est ouverte elle n'aura pas été supprimé au moment du cache turbo.
        // On fait donc le ménage avant que turbo ne sauvegarde son cache.
        document.addEventListener('turbo:before-cache', () => {
            this.removeAll()
        });
    }

    async add(event) {
        if (event.detail.stackId !== this.element.id) {
            return
        }

        let clone = this.modalModelTarget.cloneNode(true)

        delete clone.dataset.globalModalStackTarget

        clone.dataset.modalAutoOpenValue = 'true'
        // Pour ne pas avoir deux fois le même form dans le dom, on supprime les offcanvas quand ils sont fermés.
        clone.dataset.modalRemoveOnCloseValue = 'true'

        if (event.detail.frameSrc && event.detail.frameId) {
            let turboFrame = clone.querySelector('turbo-frame')
            turboFrame.setAttribute('src', event.detail.frameSrc)
            turboFrame.setAttribute('id', event.detail.frameId)

            if (event.detail.frameTarget !== '') {
                turboFrame.setAttribute('target', event.detail.frameTarget)
            } else {
                turboFrame.removeAttribute('target');
            }
        }

        if (event.detail.closeOnSuccess !== null) {
            clone.dataset.modalCloseOnSuccessValue = event.detail.closeOnSuccess
        }

        if (event.detail.stayOnSuccess
            && event.detail.stayOnSuccess !== null) {
            clone.dataset.modalStayOnSuccessValue = event.detail.stayOnSuccess
        }

        if (event.detail.relatedTurboFrames
            && event.detail.relatedTurboFrames !== null
            && event.detail.relatedTurboFrames.length > 0
        ) {
            clone.dataset.modalRelatedTurboFramesValue = JSON.stringify(event.detail.relatedTurboFrames)
        }

        console.log('Modal Stack (' + event.detail.stackId + ') -> Add', event.detail, clone.dataset)

        this.element.append(clone)
    }

    removeAll() {
        let newModalModel = this.modalModelTarget.cloneNode(true)
        this.element.innerHTML = ''
        this.element.append(newModalModel)
    }
}
