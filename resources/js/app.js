import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.showNotification = function(message, type = 'success') {
    if (!message) return; // Don't show empty notifications
    
    window.dispatchEvent(new CustomEvent('notify', {
        detail: {
            id: Date.now(),
            message: message,
            type: type
        }
    }));
};
