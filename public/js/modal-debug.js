/**
 * Modal Debug Helper
 * Script to help diagnose modal issues and ensure they're working properly
 */

console.log('Modal Debug Helper loaded');

// Wait until the page is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing modal debug helpers');

    // Check for modal elements
    const modalIds = ['orderActionModal', 'paymentProofModal'];
    modalIds.forEach(id => {
        const modal = document.getElementById(id);
        console.log(`Modal ${id} found: ${!!modal}`);
        if (modal) {
            console.log(`- Display: ${getComputedStyle(modal).display}`);
            console.log(`- Z-index: ${getComputedStyle(modal).zIndex}`);
            console.log(`- Classes: ${modal.className}`);
        }
    });

    // Add test buttons to the page for direct testing
    addTestButtons();

    // Make global modal functions available
    window.testShowModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal ${modalId} not found`);
            return;
        }

        console.log(`Manually showing modal ${modalId}`);

        // Force display
        modal.style.display = 'flex';
        modal.classList.remove('hidden');

        // Add show class after a brief delay (for animations)
        setTimeout(() => {
            modal.classList.add('show');
            console.log(`Added 'show' class to ${modalId}`);
        }, 10);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    };

    window.testCloseModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal ${modalId} not found`);
            return;
        }

        console.log(`Manually closing modal ${modalId}`);

        // Remove show class first (for animations)
        modal.classList.remove('show');

        // Hide after a brief delay
        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            console.log(`Hidden ${modalId}`);
        }, 150);

        // Restore body scroll
        document.body.style.overflow = '';
    };
});

function addTestButtons() {
    // Check if test buttons already exist
    if (document.querySelector('#modal-debug-controls')) {
        return;
    }

    // Create debug control panel
    const controlPanel = document.createElement('div');
    controlPanel.id = 'modal-debug-controls';
    controlPanel.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid #3b82f6;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-family: sans-serif;
        font-size: 14px;
    `;

    // Add heading
    const heading = document.createElement('div');
    heading.textContent = 'Modal Debug Controls';
    heading.style.fontWeight = 'bold';
    heading.style.marginBottom = '8px';
    controlPanel.appendChild(heading);

    // Add buttons for testing each modal
    addButton(controlPanel, 'Test Order Action Modal', () => window.testShowModal('orderActionModal'));
    addButton(controlPanel, 'Test Payment Proof Modal', () => window.testShowModal('paymentProofModal'));
    addButton(controlPanel, 'Close All Modals', () => {
        if (typeof window.closeAllModals === 'function') {
            window.closeAllModals();
        } else {
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                window.testCloseModal(modal.id);
            });
        }
    });

    // Add to body
    document.body.appendChild(controlPanel);
}

function addButton(parent, text, onClick) {
    const button = document.createElement('button');
    button.textContent = text;
    button.style.cssText = `
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
        transition: background-color 0.2s;
    `;
    button.addEventListener('mouseover', () => {
        button.style.backgroundColor = '#2563eb';
    });
    button.addEventListener('mouseout', () => {
        button.style.backgroundColor = '#3b82f6';
    });
    button.addEventListener('click', onClick);
    parent.appendChild(button);
    return button;
}
