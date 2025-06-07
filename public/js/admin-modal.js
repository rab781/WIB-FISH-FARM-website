/**
 * Admin Modal System
 * Reliable modal handling for admin interface
 */

// Direct modal display function that works without dependencies
function showAdminModal(modalElementId) {
    console.log('[ModalJS] showAdminModal called for:', modalElementId);

    // Accept either ID or element
    let modal = typeof modalElementId === 'string' ? document.getElementById(modalElementId) : modalElementId;

    if (!modal) {
        console.error('[ModalJS] Error: Modal element not found for ID/element:', modalElementId);
        // Mungkin tambahkan alert sederhana atau log ke debug div di sini
        // Misalnya: logToDebugDiv(`Modal element not found: ${modalElementId}`, 'error');
        return false;
    }

    // Close any currently open modals first to prevent overlaps, but don't close the modal being shown
    closeAllAdminModals(modal);

    // Remove hidden class first
    modal.classList.remove('hidden');

    // Add show class
    modal.classList.add('show');

    // Apply styles to make it visible and centered - AGGRESSIVE APPROACH
    modal.style.cssText = '';  // Clear any existing styles first

    // Set individual properties with maximum priority
    modal.style.setProperty('display', 'flex', 'important');
    modal.style.setProperty('position', 'fixed', 'important');
    modal.style.setProperty('top', '0', 'important');
    modal.style.setProperty('left', '0', 'important');
    modal.style.setProperty('right', '0', 'important');
    modal.style.setProperty('bottom', '0', 'important');
    modal.style.setProperty('width', '100vw', 'important');
    modal.style.setProperty('height', '100vh', 'important');
    modal.style.setProperty('z-index', '99999', 'important');
    modal.style.setProperty('visibility', 'visible', 'important');
    modal.style.setProperty('opacity', '1', 'important');
    modal.style.setProperty('background-color', 'rgba(0, 0, 0, 0.5)', 'important');
    modal.style.setProperty('align-items', 'center', 'important');
    modal.style.setProperty('justify-content', 'center', 'important');
    modal.style.setProperty('pointer-events', 'auto', 'important');
    modal.style.setProperty('backdrop-filter', 'blur(2px)', 'important');

    // Force remove any conflicting attributes
    modal.removeAttribute('hidden');
    modal.removeAttribute('aria-hidden');
    modal.setAttribute('aria-hidden', 'false');
    modal.setAttribute('role', 'dialog');

    // Also force the modal content to be visible
    const modalContent = modal.querySelector('.modal-content, .modal-dialog');
    if (modalContent) {
        modalContent.style.setProperty('display', 'block', 'important');
        modalContent.style.setProperty('visibility', 'visible', 'important');
        modalContent.style.setProperty('opacity', '1', 'important');
        modalContent.style.setProperty('z-index', '100000', 'important');
        modalContent.style.setProperty('position', 'relative', 'important');
        modalContent.style.setProperty('margin', 'auto', 'important');
        modalContent.style.setProperty('background', 'white', 'important');
        modalContent.style.setProperty('border-radius', '8px', 'important');
        modalContent.style.setProperty('max-width', '600px', 'important');
        modalContent.style.setProperty('width', '90%', 'important');
        modalContent.style.setProperty('box-shadow', '0 10px 25px rgba(0, 0, 0, 0.3)', 'important');
    }

    // Prevent body scrolling
    document.body.style.overflow = 'hidden';

    console.log('[ModalJS] Modal display state after showAdminModal:', {
        id: modal.id,
        display: getComputedStyle(modal).display,
        visibility: getComputedStyle(modal).visibility,
        opacity: getComputedStyle(modal).opacity,
        zIndex: getComputedStyle(modal).zIndex, // Log z-index
        classList: Array.from(modal.classList)
    });

    // Tambahkan sedikit delay untuk visualisasi jika ada transisi CSS
    setTimeout(() => {
        if (getComputedStyle(modal).display === 'none' || getComputedStyle(modal).visibility === 'hidden' || getComputedStyle(modal).opacity === '0') {
            console.error('[ModalJS] CRITICAL ERROR: Modal is still not visible after attempting to show. Forcing display properties again.', modal.id);
            // Fallback: Jika masih tidak terlihat, terapkan ulang style paling dasar
            modal.style.display = 'flex';
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';
            modal.style.zIndex = '99999'; // Pastikan z-index tertinggi
        }
    }, 50); // Beri waktu singkat untuk CSS untuk di-apply

    return true;
}

// Expose key functions to global scope for cross-file access
window.showAdminModal = showAdminModal;

// Function to close all admin modals, optionally excluding one
function closeAllAdminModals(excludeModal = null) {
    console.log('[ModalJS] closeAllAdminModals called. Excluding:', excludeModal ? excludeModal.id : 'none');

    try {
        // Find all potential modals by their IDs
        const modals = document.querySelectorAll('#orderActionModal, #paymentProofModal');
        console.log('[ModalJS] Found ' + modals.length + ' modals to potentially close.');

        modals.forEach(modal => {
            if (modal === excludeModal) {
                console.log('[ModalJS] Skipping excluded modal:', modal.id);
                return; // Skip this modal
            }

            // Check if modal is currently visible (by class or computed style)
            if (modal.classList.contains('show') || getComputedStyle(modal).display !== 'none') {
                console.log('[ModalJS] Closing modal:', modal.id || 'unnamed-modal');

                // Hide modal with multiple approaches for maximum compatibility
                modal.style.cssText = 'display: none !important;'; // Force hide
                // No need for setAttribute('style', ...) as style.cssText is usually enough.

                // Update classes
                modal.classList.remove('show');
                modal.classList.add('hidden');

                // Clear any form fields if applicable (for orderActionModal)
                if (modal.id === 'orderActionModal') {
                    console.log('[ModalJS] Resetting order action form fields.');
                    const form = modal.querySelector('form');
                    if (form) {
                        const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea');
                        inputs.forEach(input => {
                            input.value = '';
                        });
                        // Also hide any conditional containers
                        const cancelContainer = document.getElementById('orderCancelReasonContainer');
                        const shippingContainer = document.getElementById('orderShippingContainer');
                        if (cancelContainer) cancelContainer.classList.add('hidden');
                        if (shippingContainer) shippingContainer.classList.add('hidden');
                    }
                }
            }
        });

        // Enable scrolling again, only if no other modal is currently visible
        // This check ensures we don't re-enable scroll if another modal is meant to stay open
        if (!document.querySelector('#orderActionModal.show, #paymentProofModal.show')) {
             document.body.style.overflow = '';
             console.log('[ModalJS] Body scroll re-enabled.');
        }
        console.log('[ModalJS] Modals closed successfully.');

        return true;
    } catch (err) {
        console.error('[ModalJS] Error closing modals:', err);
        return false;
    }
}

// Function to set up admin order action modal content and then show it
function setupAdminOrderAction(actionType, orderId) {
    console.log('[ModalJS] setupAdminOrderAction called with:', actionType, orderId);

    if (!actionType || !orderId) {
        console.error('[ModalJS] Missing required parameters for setupAdminOrderAction.');
        alert('Error: Missing required parameters for order action. Please check console.');
        return false;
    }

    let title = '', text = '', buttonText = '', formAction = '';
    let type = null; // 'shipping' or 'cancel' for conditional fields

    // Get the modal element
    const modal = document.getElementById('orderActionModal');
    if (!modal) {
        console.error('[ModalJS] Error: Modal element not found: orderActionModal. Cannot setup.');
        alert('Error: Modal element not found. Please reload the page and try again.');
        return false;
    }

    // Reset conditional containers (hide all by default)
    const cancelContainer = document.getElementById('orderCancelReasonContainer');
    const shippingContainer = document.getElementById('orderShippingContainer');
    if (cancelContainer) {
        cancelContainer.classList.add('hidden');
        cancelContainer.querySelector('textarea').value = ''; // Clear text area
    }
    if (shippingContainer) {
        shippingContainer.classList.add('hidden');
        shippingContainer.querySelector('input').value = ''; // Clear input field
    }

    // Configure modal based on action type
    switch (actionType) {
        case 'confirm-payment':
            title = 'Konfirmasi Pembayaran';
            text = 'Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan #' + orderId + '?';
            buttonText = 'Konfirmasi Pembayaran';
            formAction = window.location.origin + `/admin/pesanan/${orderId}/confirm-payment`;
            break;
        case 'process-order':
            title = 'Proses Pesanan';
            text = 'Apakah Anda yakin ingin memproses pesanan #' + orderId + '?';
            buttonText = 'Proses Pesanan';
            formAction = window.location.origin + `/admin/pesanan/${orderId}/process`;
            break;
        case 'ship-order':
            title = 'Kirim Pesanan';
            text = 'Masukkan nomor resi pengiriman untuk pesanan #' + orderId + '. Setelah dikirim, status pesanan akan diperbarui.';
            buttonText = 'Kirim Pesanan';
            formAction = window.location.origin + `/admin/pesanan/${orderId}/ship`;
            type = 'shipping'; // Show shipping field
            break;
        case 'cancel-order':
            title = 'Batalkan Pesanan';
            text = 'Apakah Anda yakin ingin membatalkan pesanan #' + orderId + '? Berikan alasan pembatalan.';
            buttonText = 'Batalkan Pesanan';
            formAction = window.location.origin + `/admin/pesanan/${orderId}/cancel`;
            type = 'cancel'; // Show cancellation reason field
            break;
        case 'complete-order':
            title = 'Selesaikan Pesanan';
            text = 'Apakah Anda yakin ingin menandai pesanan #' + orderId + ' sebagai selesai? Aksi ini tidak dapat dibatalkan.';
            buttonText = 'Tandai Selesai';
            formAction = window.location.origin + `/admin/pesanan/${orderId}/complete`;
            break;
        default:
            console.error('[ModalJS] Unrecognized action type:', actionType);
            alert('Aksi tidak dikenal.');
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
    if (modalForm) {
        modalForm.action = formAction;
        modalForm.method = 'POST'; // Ensure method is POST for actions
        console.log('[ModalJS] Form action set to:', formAction);
        console.log('[ModalJS] Form method set to:', modalForm.method);
        console.log('[ModalJS] Form element:', modalForm);
    } else {
        console.error('[ModalJS] Form element not found!');
    }


    // Show specific containers based on action type
    if (type === 'shipping' && shippingContainer) {
        shippingContainer.classList.remove('hidden');
    } else if (type === 'cancel' && cancelContainer) {
        cancelContainer.classList.remove('hidden');
    }

    // Show the order action modal - try standard approach first, fallback to nuclear
    const showResult = showAdminModal('orderActionModal');

    // If standard approach fails, try nuclear approach after a short delay
    setTimeout(() => {
        const modal = document.getElementById('orderActionModal');
        if (modal && getComputedStyle(modal).display === 'none') {
            console.log('[ModalJS] Standard approach failed, trying nuclear approach...');
            ultimateModalFix('orderActionModal');
        }
    }, 200);

    return showResult;
}

// Global functions for direct access in Blade templates
// These ensure that `window.showAdminModal`, etc., are always available.
window.showAdminModal = showAdminModal;
window.closeAllAdminModals = closeAllAdminModals;
window.setupAdminOrderAction = setupAdminOrderAction;

// Function to copy Resi Number
function copyResi() {
    const resiNumberInput = document.getElementById('resiNumber');
    if (resiNumberInput) {
        resiNumberInput.select();
        resiNumberInput.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        alert('Nomor Resi berhasil disalin: ' + resiNumberInput.value);
    }
}
window.copyResi = copyResi; // Make it global

// --- Main Event Listener Setup ---
document.addEventListener('DOMContentLoaded', function() {
    console.log('[ModalJS] DOMContentLoaded event fired. Attaching event listeners.');

    // --- Debug Log Initialization ---
    const debugLogDiv = document.getElementById('debugLog');
    const clearLogBtn = document.getElementById('clearLog');

    function logToDebugDiv(message, type = 'info') {
        if (!debugLogDiv) return;
        const timestamp = new Date().toLocaleTimeString();
        const logItem = document.createElement('div');
        let className = '';
        switch (type) {
            case 'error': className = 'text-red-500'; break;
            case 'success': className = 'text-green-400'; break;
            case 'warning': className = 'text-yellow-400'; break;
            case 'action': className = 'text-blue-400'; break;
            default: className = 'text-green-500'; // Default to green for general info
        }
        logItem.className = className;
        logItem.innerHTML = `[${timestamp}] ${message}`;
        debugLogDiv.appendChild(logItem);
        debugLogDiv.scrollTop = debugLogDiv.scrollHeight; // Auto-scroll
    }

    if (debugLogDiv) {
        logToDebugDiv('Debug log initialized and ready.', 'success');
        if (clearLogBtn) {
            clearLogBtn.addEventListener('click', function() {
                debugLogDiv.innerHTML = '<div>[LOG] Log cleared - ' + new Date().toLocaleTimeString() + '</div>';
                logToDebugDiv('Debug log cleared.', 'success');
            });
        }
    } else {
        console.warn('[ModalJS] Debug log div not found. Console will be primary log.');
    }


    // --- Attach event listeners to Order Action Buttons ---
    const orderActionButtons = document.querySelectorAll('.confirm-payment, .process-order, .ship-order, .cancel-order, .complete-order');
    orderActionButtons.forEach(button => {
        // Remove existing onclick attribute to prevent double-firing or conflicts
        if (button.hasAttribute('onclick')) {
            logToDebugDiv(`Removing redundant 'onclick' attribute from action button: ${button.textContent.trim()}`, 'warning');
            button.removeAttribute('onclick');
        }

        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission or link navigation

            const actionType = this.classList.contains('confirm-payment') ? 'confirm-payment' :
                             this.classList.contains('process-order') ? 'process-order' :
                             this.classList.contains('ship-order') ? 'ship-order' :
                             this.classList.contains('cancel-order') ? 'cancel-order' :
                             this.classList.contains('complete-order') ? 'complete-order' : null;

            const orderId = this.dataset.id; // Get data-id attribute

            logToDebugDiv(`Action button clicked: Type=${actionType}, OrderID=${orderId}`, 'action');
            if (actionType && orderId) {
                setupAdminOrderAction(actionType, orderId);
            } else {
                logToDebugDiv('Error: Could not determine actionType or orderId from button.', 'error');
                console.error('[ModalJS] Could not determine actionType or orderId from button:', this);
            }
        });
        logToDebugDiv(`Attached listener to: ${button.textContent.trim()} (data-id: ${button.dataset.id})`, 'info');
    });

    // Check if the new modal system is active to prevent conflicts
    if (window.modalSystemInitialized) {
        console.log('[LEGACY] New modal system detected, disabling legacy event handlers');
        return;
    }

    // --- Attach event listener for "Lihat Bukti Pembayaran" button ---
    const paymentProofButton = document.querySelector('.trigger-payment-proof-modal');
    if (paymentProofButton) {
        // Remove existing onclick attribute
        if (paymentProofButton.hasAttribute('onclick')) {
            logToDebugDiv("Removing redundant 'onclick' attribute from 'Lihat Bukti Pembayaran' button.", 'warning');
            paymentProofButton.removeAttribute('onclick');
        }

        paymentProofButton.addEventListener('click', function(event) {
            event.preventDefault();
            logToDebugDiv('Lihat Bukti Pembayaran button clicked. Showing paymentProofModal.', 'action');
            showAdminModal('paymentProofModal');
        });
        logToDebugDiv("Attached listener to 'Lihat Bukti Pembayaran' button.", 'info');
    } else {
        logToDebugDiv('Payment proof button (.trigger-payment-proof-modal) not found on this page.', 'warning');
    }

    // --- Attach event listener for 'Copy Resi' button ---
    const copyResiButton = document.querySelector('.copy-resi-button');
    if (copyResiButton) {
        if (copyResiButton.hasAttribute('onclick')) {
            logToDebugDiv("Removing redundant 'onclick' attribute from 'Copy Resi' button.", 'warning');
            copyResiButton.removeAttribute('onclick');
        }
        copyResiButton.addEventListener('click', function(event) {
            event.preventDefault();
            logToDebugDiv('Copy Resi button clicked.', 'action');
            copyResi(); // Call the global copyResi function
        });
        logToDebugDiv("Attached listener to 'Copy Resi' button.", 'info');
    }

    // --- Attach event listeners for modal close buttons ---
    document.querySelectorAll('.modal-close').forEach(button => {
        if (button.hasAttribute('onclick')) {
            logToDebugDiv(`Removing redundant 'onclick' attribute from close button: ${button.textContent.trim()}`, 'warning');
            button.removeAttribute('onclick');
        }
        button.addEventListener('click', function(event) {
            event.preventDefault();
            logToDebugDiv('Modal close button clicked. Closing all modals.', 'action');
            closeAllAdminModals();
        });
        logToDebugDiv(`Attached listener to modal close button.`, 'info');
    });

    // --- Attach event listener for modal backdrop clicks ---
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        // Ensure backdrop itself closes the modal, but not clicks on modal content
        backdrop.addEventListener('click', function(event) {
            if (event.target === this) { // Only close if the backdrop itself was clicked
                logToDebugDiv('Modal backdrop clicked. Closing all modals.', 'action');
                closeAllAdminModals();
            }
        });
        logToDebugDiv(`Attached listener to modal backdrop.`, 'info');
    });

    // --- ESC key to close modals ---
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            logToDebugDiv('Escape key pressed. Closing modals.', 'action');
            closeAllAdminModals();
        }
    });

    logToDebugDiv('All primary event listeners attached.', 'success');

    // Setup form submission debugging if not using new modal system
    if (!window.modalSystemInitialized) {
        setupFormSubmissionDebugging();
    }

    // --- Optional: Self-test/Diagnostic function ---
    // This can be called from console to test modal system manually.
    // console.log('[ModalJS] For self-test, try: window.testAdminModal();');
    // console.log('[ModalJS] For full diagnostic, try: window.diagnoseAdminModalSystem();');
});


// Optional: Test function for debugging directly from console
window.testAdminModal = function() {
    console.log('🧪 [ModalJS] Running admin-modal.js self-test...');
    const orderModal = document.getElementById('orderActionModal');
    const paymentModal = document.getElementById('paymentProofModal');

    if (orderModal) {
        console.log('[ModalJS] Showing orderActionModal (test)...');
        showAdminModal(orderModal.id);
        setTimeout(() => {
            console.log('[ModalJS] Hiding orderActionModal (test)...');
            closeAllAdminModals();
        }, 2000);
    } else {
        console.warn('[ModalJS] orderActionModal not found for self-test.');
    }

    // Sequence the second modal test
    setTimeout(() => {
        if (paymentModal) {
            console.log('[ModalJS] Showing paymentProofModal (test)...');
            showAdminModal(paymentModal.id);
            setTimeout(() => {
                console.log('[ModalJS] Hiding paymentProofModal (test)...');
                closeAllAdminModals();
            }, 2000); // Shorter duration for sequenced test
        } else {
            console.warn('[ModalJS] paymentProofModal not found for self-test.');
        }
    }, 2500); // Start payment modal test after order modal closes

    console.log('🧪 [ModalJS] Self-test sequence initiated. Check console for steps.');
};

// Optional: Global diagnostic function to check modal system state
window.diagnoseAdminModalSystem = function() {
    console.clear();
    console.log('--- ADMIN MODAL SYSTEM DIAGNOSTIC ---');
    console.log('Function showAdminModal exists:', typeof window.showAdminModal === 'function');
    console.log('Function closeAllAdminModals exists:', typeof window.closeAllAdminModals === 'function');
    console.log('Function setupAdminOrderAction exists:', typeof window.setupAdminOrderAction === 'function');
    console.log('Function copyResi exists:', typeof window.copyResi === 'function');

    const modals = {
        orderAction: document.getElementById('orderActionModal'),
        paymentProof: document.getElementById('paymentProofModal')
    };

    console.log('\n--- Modal Elements Status ---');
    for (const key in modals) {
        const modal = modals[key];
        if (modal) {
            console.log(`Modal ${key} (${modal.id}):`);
            console.log(`  - Display: ${getComputedStyle(modal).display}`);
            console.log(`  - Visibility: ${getComputedStyle(modal).visibility}`);
            console.log(`  - Opacity: ${getComputedStyle(modal).opacity}`);
            console.log(`  - z-index: ${getComputedStyle(modal).zIndex}`);
            console.log(`  - ClassList: ${Array.from(modal.classList).join(', ')}`);
            if (modal.id === 'orderActionModal') {
                const form = modal.querySelector('form');
                console.log(`  - Form Action: ${form ? form.action : 'N/A'}`);
                console.log(`  - Form Method: ${form ? form.method : 'N/A'}`);
            } else if (modal.id === 'paymentProofModal') {
                const img = modal.querySelector('#paymentProofImage');
                console.log(`  - Image src: ${img ? img.src : 'N/A'}`);
                console.log(`  - Image onerror: ${img && img.onerror ? 'Present' : 'N/A'}`);
            }
        } else {
            console.warn(`Modal ${key} not found.`);
        }
    }

    console.log('\n--- Action Buttons Status ---');
    const buttons = document.querySelectorAll('.confirm-payment, .process-order, .ship-order, .cancel-order, .complete-order, .trigger-payment-proof-modal, .copy-resi-button');
    if (buttons.length > 0) {
        buttons.forEach((btn, i) => {
            console.log(`Button ${i + 1} (${btn.textContent.trim()}):`);
            console.log(`  - Classes: ${Array.from(btn.classList).join(', ')}`);
            console.log(`  - data-id: ${btn.dataset.id || 'N/A'}`);
            console.log(`  - onclick attribute: ${btn.getAttribute('onclick') || 'None (Should be handled by JS)'}`);
            // Note: Cannot directly list JS event listeners via DOM API in console, but can confirm attachment via logs.
        });
    } else {
        console.warn('No action buttons found.');
    }

    console.log('\n--- End Diagnostic ---');
};

// Ultimate Modal Fix - Nuclear Approach
function ultimateModalFix(modalElementId) {
    console.log('[UltimateFix] Starting nuclear modal display approach for:', modalElementId);

    const modal = typeof modalElementId === 'string' ? document.getElementById(modalElementId) : modalElementId;
    if (!modal) {
        console.error('[UltimateFix] Modal not found:', modalElementId);
        return false;
    }

    // STEP 1: Check and fix parent overflow issues
    let parent = modal.parentElement;
    const originalParentStyles = [];
    let level = 0;

    while (parent && level < 10) {
        const computedStyle = getComputedStyle(parent);

        // Store original styles for restoration
        originalParentStyles.push({
            element: parent,
            overflow: parent.style.overflow,
            transform: parent.style.transform,
            clip: parent.style.clip,
            clipPath: parent.style.clipPath
        });

        // Fix common clipping issues
        if (computedStyle.overflow === 'hidden' || computedStyle.overflow === 'clip') {
            console.log('[UltimateFix] Found parent with overflow hidden, fixing:', parent.tagName, parent.className);
            parent.style.setProperty('overflow', 'visible', 'important');
        }

        // Remove transforms that might affect positioning
        if (computedStyle.transform !== 'none') {
            console.log('[UltimateFix] Found parent with transform, clearing:', parent.tagName);
            parent.style.setProperty('transform', 'none', 'important');
        }

        // Clear clip paths
        if (computedStyle.clipPath !== 'none') {
            parent.style.setProperty('clip-path', 'none', 'important');
        }

        parent = parent.parentElement;
        level++;
    }

    // STEP 2: Nuclear modal styling - append to body for maximum isolation
    const body = document.body;

    // Create a portal container that's guaranteed to be visible
    let portalContainer = document.getElementById('modal-portal-container');
    if (!portalContainer) {
        portalContainer = document.createElement('div');
        portalContainer.id = 'modal-portal-container';
        portalContainer.style.cssText = `
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 999999 !important;
            pointer-events: none !important;
            overflow: visible !important;
        `;
        body.appendChild(portalContainer);
    }

    // Clone the modal and move to portal
    const modalClone = modal.cloneNode(true);
    modalClone.id = modal.id + '-portal';

    // Apply nuclear styling to cloned modal
    modalClone.style.cssText = `
        display: flex !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 1000000 !important;
        background-color: rgba(0, 0, 0, 0.7) !important;
        align-items: center !important;
        justify-content: center !important;
        pointer-events: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
        overflow: visible !important;
        transform: none !important;
        clip: none !important;
        clip-path: none !important;
        backdrop-filter: blur(3px) !important;
    `;

    // Style the modal content
    const modalContent = modalClone.querySelector('.modal-content, .modal-dialog');
    if (modalContent) {
        modalContent.style.cssText = `
            background: white !important;
            border-radius: 8px !important;
            max-width: 600px !important;
            width: 90% !important;
            max-height: 80vh !important;
            overflow-y: auto !important;
            position: relative !important;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5) !important;
            margin: auto !important;
            z-index: 1000001 !important;
            pointer-events: auto !important;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
            transform: scale(1) !important;
        `;
    }

    // Clear portal and add cloned modal
    portalContainer.innerHTML = '';
    portalContainer.appendChild(modalClone);

    // Hide original modal
    modal.style.display = 'none';

    // Setup close handlers for the cloned modal
    setupModalCloseHandlers(modalClone, modal, originalParentStyles);

    // Prevent body scroll
    body.style.overflow = 'hidden';

    console.log('[UltimateFix] Nuclear modal display complete. Modal should now be visible.');

    // Force focus to modal for accessibility
    setTimeout(() => {
        modalClone.focus();
    }, 100);

    return true;
}

// Expose ultimateModalFix to global scope for cross-file access
window.ultimateModalFix = ultimateModalFix;

function setupModalCloseHandlers(clonedModal, originalModal, originalParentStyles) {
    // Close on backdrop click
    clonedModal.addEventListener('click', function(e) {
        if (e.target === clonedModal) {
            closeNuclearModal(clonedModal, originalModal, originalParentStyles);
        }
    });

    // Close on close buttons
    const closeButtons = clonedModal.querySelectorAll('[data-dismiss="modal"], .close, .modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeNuclearModal(clonedModal, originalModal, originalParentStyles);
        });
    });

    // Close on escape key
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            closeNuclearModal(clonedModal, originalModal, originalParentStyles);
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);
}

function closeNuclearModal(clonedModal, originalModal, originalParentStyles) {
    console.log('[UltimateFix] Closing nuclear modal');

    // Remove cloned modal
    const portalContainer = document.getElementById('modal-portal-container');
    if (portalContainer) {
        portalContainer.remove();
    }

    // Restore original modal
    originalModal.style.display = 'none';
    originalModal.classList.add('hidden');
    originalModal.classList.remove('show');

    // Restore parent styles
    originalParentStyles.forEach(styleInfo => {
        const { element, overflow, transform, clip, clipPath } = styleInfo;
        if (overflow) element.style.overflow = overflow;
        else element.style.removeProperty('overflow');

        if (transform) element.style.transform = transform;
        else element.style.removeProperty('transform');

        if (clip) element.style.clip = clip;
        else element.style.removeProperty('clip');

        if (clipPath) element.style.clipPath = clipPath;
        else element.style.removeProperty('clip-path');
    });

    // Restore body scroll
    document.body.style.overflow = '';

    console.log('[UltimateFix] Nuclear modal closed and styles restored');
}

// Form submission debugging function (called from DOMContentLoaded)
function setupFormSubmissionDebugging() {
    const orderActionForm = document.getElementById('orderActionForm');
    if (orderActionForm) {
        orderActionForm.addEventListener('submit', function(e) {
            console.log('[ModalJS] ================================');
            console.log('[ModalJS] FORM SUBMISSION EVENT TRIGGERED');
            console.log('[ModalJS] ================================');
            console.log('[ModalJS] Form action:', this.action);
            console.log('[ModalJS] Form method:', this.method);
            console.log('[ModalJS] Current URL:', window.location.href);
            console.log('[ModalJS] Form element:', this);

            const formData = new FormData(this);
            console.log('[ModalJS] Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            // Check if action is properly set
            if (!this.action || this.action.includes('undefined') || this.action === window.location.href) {
                e.preventDefault();
                console.error('[ModalJS] ❌ FORM ACTION ERROR - Form action is not properly set:', this.action);
                alert('Error: Form action not properly set. Action: ' + this.action);
                return false;
            }

            // Check if the action URL is different from current page
            if (this.action === window.location.href) {
                e.preventDefault();
                console.error('[ModalJS] ❌ FORM ACTION ERROR - Form action is same as current URL:', this.action);
                alert('Error: Form action is same as current page URL. This will cause a Method Not Allowed error.');
                return false;
            }

            // Additional check: Ensure action URL contains a valid route
            const actionPath = this.action.replace(window.location.origin, '');
            const validActions = ['/confirm-payment', '/process', '/ship', '/cancel', '/complete'];
            const hasValidAction = validActions.some(action => actionPath.includes(action));

            if (!hasValidAction) {
                e.preventDefault();
                console.error('[ModalJS] ❌ FORM ACTION ERROR - Form action does not contain valid action route:', actionPath);
                alert('Error: Form action does not contain a valid action route. Action: ' + actionPath);
                return false;
            }

            console.log('[ModalJS] ✅ Form validation passed, submitting to:', this.action);
        });
        console.log('[ModalJS] Form submission event listener attached to orderActionForm');
    } else {
        console.error('[ModalJS] orderActionForm not found - form submission debugging not available');
    }
}
