/**
 * Modal System JS
 * Handles modal dialogs throughout the application
 */

// Global modal functions
function closeAllModals() {
    // Find all modal elements
    const modals = document.querySelectorAll('[id$="Modal"]');

    // Close each modal
    modals.forEach(modal => {
        if (modal) {
            modal.classList.remove('modal-overlay', 'show');
            modal.classList.add('modal-hidden');
        }
    });

    // Remove any body scroll lock
    document.body.style.overflow = '';
}

function showModal(modal) {
    if (!modal) {
        console.error('Modal element is null or undefined');
        return;
    }

    // Close other modals first
    closeAllModals();

    // Show the modal using our custom classes
    modal.classList.remove('modal-hidden');
    modal.classList.add('modal-overlay', 'show');

    // Log for debugging
    console.log('Modal visibility: ' + getComputedStyle(modal).display);
    console.log('Modal z-index: ' + getComputedStyle(modal).zIndex);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Set up modal events when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Find all modals
    const modals = document.querySelectorAll('[id$="Modal"]');

    // Set up backdrop click handlers
    modals.forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeAllModals();
                }
            });
        }
    });

    // Close buttons
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', closeAllModals);
    });

    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
});
