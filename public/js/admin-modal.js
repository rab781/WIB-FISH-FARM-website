/**
 * Admin Modal System
 * Reliable modal handling for admin interface
 */

// Direct modal display function that works without dependencies
function showAdminModal(modalElement) {
    console.log('showAdminModal called');

    // Accept either ID or element
    let modal = modalElement;
    if (typeof modalElement === 'string') {
        modal = document.getElementById(modalElement);
    }

    if (!modal) {
        console.error('Modal not found');
        return false;
    }

    // Force display style with !important
    modal.style.cssText = `
        display: flex !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 9999 !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
        visibility: visible !important;
        opacity: 1 !important;
    `;

    // Also try attribute-based approach for extra reliability
    modal.setAttribute('style',
        'display: flex !important; ' +
        'position: fixed !important; ' +
        'top: 0 !important; ' +
        'left: 0 !important; ' +
        'right: 0 !important; ' +
        'bottom: 0 !important; ' +
        'z-index: 9999 !important; ' +
        'align-items: center !important; ' +
        'justify-content: center !important; ' +
        'background-color: rgba(0, 0, 0, 0.7) !important; ' +
        'visibility: visible !important; ' +
        'opacity: 1 !important;'
    );

    // Remove hidden class
    modal.classList.remove('hidden');

    // Add show class
    if (!modal.classList.contains('show')) {
        modal.classList.add('show');
    }

    // Prevent scrolling
    document.body.style.overflow = 'hidden';

    return true;
}

// Make function available globally
window.showAdminModal = showAdminModal;

// Ensure this works as expected
console.log('Admin Modal JS loaded successfully!');

// Function to close all admin modals
function closeAllAdminModals() {
    console.log('closeAllAdminModals called');

    try {
        // Find all visible modals with class 'show' or specific IDs
        const modals = document.querySelectorAll('#orderActionModal, #paymentProofModal, .modal.show');
        console.log('Found ' + modals.length + ' modals to close');

        modals.forEach(modal => {
            console.log('Closing modal:', modal.id || 'unnamed-modal');

            // Hide modal with multiple approaches for maximum compatibility
            modal.style.cssText = 'display: none !important;';
            modal.setAttribute('style', 'display: none !important;');

            // Update classes
            modal.classList.remove('show');
            modal.classList.add('hidden');

            // For specific/known modals, use direct approach
            if (modal.id === 'orderActionModal' || modal.id === 'paymentProofModal') {
                console.log(`Applying specific closing for ${modal.id}`);

                // Clear any form fields if applicable
                const form = modal.querySelector('form');
                if (form) {
                    console.log('Resetting form fields');
                    const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea');
                    inputs.forEach(input => {
                        input.value = '';
                    });
                }
            }
        });

        // Enable scrolling again
        document.body.style.overflow = '';
        console.log('Modals closed successfully');

        return true;
    } catch (err) {
        console.error('Error closing modals:', err);

        // Emergency fallback - try direct closing of known modals
        try {
            const orderModal = document.getElementById('orderActionModal');
            const paymentModal = document.getElementById('paymentProofModal');

            if (orderModal) {
                orderModal.style.display = 'none';
                orderModal.classList.add('hidden');
                orderModal.classList.remove('show');
            }

            if (paymentModal) {
                paymentModal.style.display = 'none';
                paymentModal.classList.add('hidden');
                paymentModal.classList.remove('show');
            }

            document.body.style.overflow = '';
            console.log('Emergency modal closing attempted');
        } catch (e) {
            console.error('Emergency modal closing also failed:', e);
        }

        return false;
    }
}

// Function for debug modal system
function debugAdminModal(modalId) {
    console.log('debugAdminModal called for', modalId);

    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return {
            success: false,
            error: 'Modal element not found',
            modalId: modalId
        };
    }

    // Collect all relevant info about the modal
    const info = {
        success: true,
        id: modal.id,
        display: getComputedStyle(modal).display,
        visibility: getComputedStyle(modal).visibility,
        opacity: getComputedStyle(modal).opacity,
        zIndex: getComputedStyle(modal).zIndex,
        classes: Array.from(modal.classList),
        hasHidden: modal.classList.contains('hidden'),
        hasShow: modal.classList.contains('show'),
        modalContent: modal.querySelector('.modal-content') ? true : false,
        modalBackdrop: modal.querySelector('.modal-backdrop') ? true : false
    };

    console.log('Modal debug information:', info);

    // Try to show the modal
    showAdminModal(modal);

    return info;
}

// Function to set up admin order action
function setupAdminOrderAction(actionType, orderId) {
    console.log('setupAdminOrderAction called with:', actionType, orderId);

    if (!actionType || !orderId) {
        console.error('Missing required parameters');
        alert('Error: Missing required parameters for order action');
        return false;
    }

    let title = '', text = '', buttonText = '', formAction = '';
    let type = null;

    // Get the modal element
    const modal = document.getElementById('orderActionModal');
    if (!modal) {
        console.error('Modal element not found: orderActionModal');
        alert('Error: Modal element not found. Please reload the page and try again.');
        return false;
    }

    // Log the current state of the modal before we modify it
    console.log('Modal before setup:', {
        display: modal.style.display,
        visibility: modal.style.visibility,
        opacity: modal.style.opacity,
        classList: Array.from(modal.classList)
    });

    // Reset containers visibility
    const cancelContainer = document.getElementById('orderCancelReasonContainer');
    const shippingContainer = document.getElementById('orderShippingContainer');

    if (cancelContainer) {
        cancelContainer.classList.add('hidden');
    } else {
        console.warn('Cancel reason container not found');
    }

    if (shippingContainer) {
        shippingContainer.classList.add('hidden');
    } else {
        console.warn('Shipping container not found');
    }

    // Configure modal based on action type
    switch (actionType) {
        case 'confirm-payment':
            title = 'Konfirmasi Pembayaran';
            text = 'Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan #' + orderId + '?';
            buttonText = 'Konfirmasi Pembayaran';
            formAction = `/admin/pesanan/${orderId}/confirm-payment`;
            break;
        case 'process-order':
            title = 'Proses Pesanan';
            text = 'Apakah Anda yakin ingin memproses pesanan #' + orderId + '?';
            buttonText = 'Proses Pesanan';
            formAction = `/admin/pesanan/${orderId}/process`;
            break;
        case 'ship-order':
            title = 'Kirim Pesanan';
            text = 'Masukkan nomor resi pengiriman untuk pesanan #' + orderId;
            buttonText = 'Kirim Pesanan';
            formAction = `/admin/pesanan/${orderId}/ship`;
            type = 'shipping';
            break;
        case 'cancel-order':
            title = 'Batalkan Pesanan';
            text = 'Apakah Anda yakin ingin membatalkan pesanan #' + orderId + '?';
            buttonText = 'Batalkan Pesanan';
            formAction = `/admin/pesanan/${orderId}/cancel`;
            type = 'cancel';
            break;
        case 'complete-order':
            title = 'Selesaikan Pesanan';
            text = 'Apakah Anda yakin ingin menandai pesanan #' + orderId + ' sebagai selesai?';
            buttonText = 'Tandai Selesai';
            formAction = `/admin/pesanan/${orderId}/complete`;
            break;
        default:
            console.error('Unrecognized action type:', actionType);
            return false;
    }

    // Update modal content
    const modalLabel = document.getElementById('orderActionModalLabel');
    const modalText = document.getElementById('orderActionText');
    const modalButton = document.getElementById('orderActionButton');
    const modalForm = document.getElementById('orderActionForm');

    if (modalLabel) modalLabel.textContent = title;
    if (modalText) modalText.textContent = text;
    if (modalButton) modalButton.textContent = buttonText;
    if (modalForm) modalForm.action = formAction;

    // Show specific containers based on action type
    if (type === 'shipping' && shippingContainer) {
        shippingContainer.classList.remove('hidden');
    } else if (type === 'cancel' && cancelContainer) {
        cancelContainer.classList.remove('hidden');
    }

    // Show the modal with full error handling
    try {
        console.log('About to call showAdminModal');
        const showResult = showAdminModal(modal);
        console.log('showAdminModal result:', showResult);

        // Double-check that the modal is visible
        setTimeout(() => {
            const isVisible = getComputedStyle(modal).display !== 'none';
            console.log('Modal visible after showAdminModal?', isVisible);

            if (!isVisible) {
                console.warn('Modal not showing - applying direct styles as fallback');
                // Force visibility directly
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
                modal.classList.add('show');
            }
        }, 100);

        return true;
    } catch (err) {
        console.error('Error showing modal:', err);
        alert('Error showing modal: ' + err.message);
        return false;
    }
}

// Direct test function for the admin modal system
function testAdminModal() {
    console.log('🧪 Testing Admin Modal System...');

    // Test the orderActionModal
    const orderActionModal = document.getElementById('orderActionModal');

    if (!orderActionModal) {
        console.error('❌ orderActionModal not found in document!');

        // Try to find it with a more thorough search
        const possibleModals = document.querySelectorAll('div[id*="Modal"], div[class*="modal"]');
        if (possibleModals.length > 0) {
            console.log('Found ' + possibleModals.length + ' possible modal elements:');
            possibleModals.forEach(m => console.log('- ' + m.id + ' (classes: ' + Array.from(m.classList).join(', ') + ')'));
        } else {
            console.log('No modal-like elements found in the document.');
        }

        return false;
    }

    console.log('✅ orderActionModal found:', orderActionModal);

    // Check modal content
    const modalContent = orderActionModal.querySelector('.modal-content');
    console.log('Modal content found:', !!modalContent);

    // Check modal backdrop
    const modalBackdrop = orderActionModal.querySelector('.modal-backdrop');
    console.log('Modal backdrop found:', !!modalBackdrop);

    // Try to show the modal
    console.log('Attempting to show the modal...');
    showAdminModal(orderActionModal);

    // Check if it's visible
    setTimeout(() => {
        const isVisible = getComputedStyle(orderActionModal).display !== 'none';
        console.log('Modal visible after showing:', isVisible);

        if (!isVisible) {
            console.error('❌ Modal did not become visible. Adding direct styles.');

            // Force display with direct style attribute
            orderActionModal.style.cssText = `
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                z-index: 9999 !important;
                align-items: center !important;
                justify-content: center !important;
                background-color: rgba(0, 0, 0, 0.7) !important;
                visibility: visible !important;
                opacity: 1 !important;
            `;

            // Check again
            setTimeout(() => {
                console.log('Modal visible after force-style:', getComputedStyle(orderActionModal).display !== 'none');
            }, 100);
        }

        // Add a timer to auto-close the modal
        console.log('Modal will auto-close in 5 seconds...');
        setTimeout(() => {
            closeAllAdminModals();
            console.log('Modal auto-closed.');
        }, 5000);
    }, 100);

    return true;
}

// Direct global diagnostic test function for the modal system
// This can be called directly from the browser console
window.diagnoseModalSystem = function() {
    console.clear();
    console.log('🔍 MODAL SYSTEM DIAGNOSTIC');
    console.log('========================');

    // Check for functions
    const functions = {
        'showAdminModal': typeof showAdminModal === 'function',
        'closeAllAdminModals': typeof closeAllAdminModals === 'function',
        'setupAdminOrderAction': typeof setupAdminOrderAction === 'function',
        'window.showAdminModal': typeof window.showAdminModal === 'function',
        'window.closeAllAdminModals': typeof window.closeAllAdminModals === 'function',
        'window.setupAdminOrderAction': typeof window.setupAdminOrderAction === 'function'
    };

    console.log('📋 Function Availability:');
    for (const [name, exists] of Object.entries(functions)) {
        console.log(`${exists ? '✅' : '❌'} ${name}`);
    }

    // Check for modal elements
    const modals = {
        'orderActionModal': document.getElementById('orderActionModal'),
        'paymentProofModal': document.getElementById('paymentProofModal')
    };

    console.log('\n📋 Modal Elements:');
    for (const [name, element] of Object.entries(modals)) {
        if (element) {
            console.log(`✅ ${name} found`);
            console.log(`   - display: ${getComputedStyle(element).display}`);
            console.log(`   - visibility: ${getComputedStyle(element).visibility}`);
            console.log(`   - classes: ${Array.from(element.classList).join(', ')}`);
        } else {
            console.log(`❌ ${name} NOT found`);
        }
    }

    // Check for action buttons
    const buttonSelectors = ['.confirm-payment', '.process-order', '.ship-order', '.cancel-order', '.complete-order'];
    const buttons = {};

    buttonSelectors.forEach(selector => {
        buttons[selector] = document.querySelectorAll(selector);
    });

    console.log('\n📋 Action Buttons:');
    for (const [selector, elements] of Object.entries(buttons)) {
        if (elements.length > 0) {
            console.log(`✅ ${selector}: ${elements.length} found`);
            elements.forEach((btn, i) => {
                console.log(`   - Button ${i+1}: ${btn.textContent.trim()}`);
                console.log(`     data-id: ${btn.getAttribute('data-id')}`);
                console.log(`     onclick: ${btn.getAttribute('onclick')}`);
            });
        } else {
            console.log(`❌ ${selector}: none found`);
        }
    }

    // Test modal opening
    console.log('\n🧪 Testing Modal System:');
    if (modals.orderActionModal) {
        console.log('Attempting to open orderActionModal...');
        try {
            showAdminModal(modals.orderActionModal);
            console.log('Modal opened successfully');

            // Auto-close after 3 seconds
            console.log('Will auto-close in 3 seconds...');
            setTimeout(() => {
                closeAllAdminModals();
                console.log('Modal closed automatically');
            }, 3000);
        } catch (err) {
            console.error('Error opening modal:', err);
        }
    } else {
        console.log('Cannot test modal opening - orderActionModal not found');
    }

    return {
        functions,
        modals,
        buttons
    };
};

// Function to force show/hide a specific modal
window.forceToggleModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error(`Modal with ID "${modalId}" not found!`);
        return false;
    }

    const isVisible = getComputedStyle(modal).display !== 'none';

    if (isVisible) {
        console.log(`Forcing ${modalId} to close`);
        modal.style.cssText = 'display: none !important;';
        modal.classList.remove('show');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    } else {
        console.log(`Forcing ${modalId} to open`);
        modal.style.cssText = `
            display: flex !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 9999 !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: rgba(0, 0, 0, 0.7) !important;
            visibility: visible !important;
            opacity: 1 !important;
        `;
        modal.classList.add('show');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    return true;
};

// Immediate self-test to confirm everything is working
(function() {
    console.log('🚀 Admin Modal Self-Test Starting...');

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Checking for essential modal elements...');

        // Check if modal elements exist
        const orderActionModal = document.getElementById('orderActionModal');
        const paymentProofModal = document.getElementById('paymentProofModal');

        console.log('orderActionModal found:', !!orderActionModal);
        console.log('paymentProofModal found:', !!paymentProofModal);

        // Add click listeners for all modal-related buttons
        document.querySelectorAll('button[onclick*="setupAdminOrderAction"]').forEach(btn => {
            console.log('Order action button found:', btn.textContent.trim());
            // Force a direct event listener in addition to the onclick attribute
            btn.addEventListener('click', function(e) {
                console.log('Button clicked via event listener:', this.textContent.trim());
                const dataset = this.dataset;
                // The original onclick should handle the action, this is just redundancy
            });
        });

        // Check modal-close buttons
        document.querySelectorAll('.modal-close').forEach(btn => {
            console.log('Close button found');
            // Add direct event listener
            btn.addEventListener('click', function(e) {
                console.log('Close button clicked via event listener');
                closeAllAdminModals();
            });
        });

        // Handle modal backdrop clicks
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            console.log('Modal backdrop found');
            // Add direct event listener
            backdrop.addEventListener('click', function(e) {
                console.log('Backdrop clicked via event listener');
                closeAllAdminModals();
            });
        });

        console.log('🏁 Admin Modal Self-Test Complete');
    });
})();

