import './bootstrap';
import './elements/turbo-echo-stream-tag';
import './libs';
import './echo';
import './stream';

import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

document.addEventListener("turbo:load", () => {
    Alpine.initTree(document.body)
})
