/**
 * SIMPLIFIED ADMIN MODAL SYSTEM
 * Version 3.0 - The ULTIMATE and reliable modal handling for admin interface
 */

// Function to handle logging to the debug div
function logToDebugDiv(message, type = 'info') {
    const debugLogDiv = document.getElementById('debugLog');
    if (!debugLogDiv) {
        console.log(`[DEBUG_DIV_FALLBACK] ${message}`);
        return;
    }
    const timestamp = new Date().toLocaleTimeString();
    const logItem = document.createElement('div');
    let className = '';
    switch (type) {
        case 'error': className = 'text-red-500'; break;
        case 'success': className = 'text-green-400'; break;
        case 'warning': className = 'text-yellow-400'; break;
        case 'action': className = 'text-blue-400'; break;
        default: className = 'text-green-500';
    }
    logItem.className = className;
    logItem.innerHTML = `[${timestamp}] ${message}`;
    debugLogDiv.appendChild(logItem);
    debugLogDiv.scrollTop = debugLogDiv.scrollHeight;
}

// Function to close all admin modals, optionally excluding one
function closeAllAdminModals(excludeModal = null) {
    logToDebugDiv(`[ModalJS] closeAllAdminModals called. Excluding: ${excludeModal ? excludeModal.id : 'none'}`, 'action');

    try {
        const modals = document.querySelectorAll('#orderActionModal, #paymentProofModal, #modal-portal-container');
        logToDebugDiv(`[ModalJS] Found ${modals.length} modals to potentially close.`, 'info');

        modals.forEach(modal => {
            if (modal === excludeModal) {
                logToDebugDiv(`[ModalJS] Skipping excluded modal: ${modal.id}`, 'info');
                return;
            }

            if (modal.classList.contains('show') || getComputedStyle(modal).display !== 'none') {
                logToDebugDiv(`[ModalJS] Closing modal: ${modal.id || 'unnamed-modal'}`, 'action');

                modal.style.setProperty('display', 'none', 'important');
                modal.style.setProperty('visibility', 'hidden', 'important');
                modal.style.setProperty('opacity', '0', 'important');
                modal.style.setProperty('pointer-events', 'none', 'important');

                modal.classList.remove('show');
                modal.classList.add('hidden');

                if (modal.id === 'orderActionModal') {
                    logToDebugDiv('[ModalJS] Resetting order action form fields.', 'info');
                    const form = modal.querySelector('form');
                    if (form) {
                        const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea');
                        inputs.forEach(input => {
                            input.value = '';
                        });
                        const cancelContainer = document.getElementById('orderCancelReasonContainer');
                        const shippingContainer = document.getElementById('orderShippingContainer');
                        if (cancelContainer) cancelContainer.classList.add('hidden');
                        if (shippingContainer) shippingContainer.classList.add('hidden');
                    }
                }
            }
            // If the modal being closed is the portal container, it will handle scroll restore
            if (modal.id === 'modal-portal-container') {
                logToDebugDiv('[ModalJS] Closing portal container, scroll restore will be handled by its specific close handler.', 'info');
            }
        });

        // Only restore scroll if no portal container is active or being closed
        if (!document.querySelector('#modal-portal-container') && !document.querySelector('#orderActionModal.show, #paymentProofModal.show')) {
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            //window.scrollTo(0, scrollPosition); // Restore scroll position
            logToDebugDiv('[ModalJS] Body scroll restored (no portal container active).', 'info');
        }

        logToDebugDiv('[ModalJS] Modals closed successfully.', 'success');
        return true;
    } catch (err) {
        console.error('[ModalJS] Error closing modals:', err);
        logToDebugDiv(`[ModalJS] Error closing modals: ${err.message}`, 'error');
        return false;
    }
}


// Ultimate Modal Fix - Nuclear Approach for guaranteed display
// This function moves the modal to a dedicated portal in the body
// and applies extreme styling to ensure visibility.
// Ultimate Modal Fix - Nuclear Approach for guaranteed display
function ultimateModalFix(modalElementId) {
    logToDebugDiv(`[UltimateFix] Starting nuclear modal display approach for: ${modalElementId}`, 'action');

    const modal = typeof modalElementId === 'string' ? document.getElementById(modalElementId) : modalElementId;
    if (!modal) {
        console.error('[UltimateFix] Modal not found:', modalElementId);
        logToDebugDiv(`[UltimateFix] ERROR: Modal element not found: ${modalElementId}`, 'error');
        return false;
    }

    // Close any other modal first, but not this one
    closeAllAdminModals(modal);

    // --- Perbaikan Scroll Lock di SINI ---
    // 1. Simpan posisi scroll saat ini
    scrollPosition = window.pageYOffset;

    // 2. Terapkan overflow: hidden dan position: fixed ke body
    //    Offset body ke posisi scroll yang disimpan
    document.body.style.setProperty('overflow', 'hidden', 'important');
    document.body.style.setProperty('position', 'fixed', 'important');
    document.body.style.setProperty('top', `-${scrollPosition}px`, 'important'); // Geser body ke atas
    document.body.style.setProperty('left', '0', 'important');
    document.body.style.setProperty('right', '0', 'important');
    logToDebugDiv(`[UltimateFix] Applied scroll lock at position: -${scrollPosition}px`, 'info');
    // --- Akhir Perbaikan Scroll Lock ---


    // STEP 1: Clear and fix parent overflow/transform issues for a few levels up
    // ... (Bagian ini tetap sama seperti yang terakhir Anda miliki) ...
    let parent = modal.parentElement;
    const originalParentStyles = [];
    let level = 0;

    while (parent && parent.tagName !== 'BODY' && level < 10) {
        const computedStyle = getComputedStyle(parent);

        originalParentStyles.push({
            element: parent,
            overflow: parent.style.overflow,
            transform: parent.style.transform,
            clip: parent.style.clip,
            clipPath: parent.style.clipPath
        });

        if (computedStyle.overflow !== 'visible' && computedStyle.overflow !== 'unset') {
            logToDebugDiv(`[UltimateFix] Found parent with non-visible overflow, fixing: ${parent.tagName}.${parent.className}`, 'warning');
            parent.style.setProperty('overflow', 'visible', 'important');
        }
        if (computedStyle.transform !== 'none' && computedStyle.transform !== 'matrix(1, 0, 0, 1, 0, 0)') {
            logToDebugDiv(`[UltimateFix] Found parent with transform, clearing: ${parent.tagName}.${parent.className}`, 'warning');
            parent.style.setProperty('transform', 'none', 'important');
        }
        if (computedStyle.clipPath !== 'none') {
            parent.style.setProperty('clip-path', 'none', 'important');
        }
        parent = parent.parentElement;
        level++;
    }

    // STEP 2: Create or reuse a portal container attached directly to the body
    // ... (Bagian ini tetap sama seperti yang terakhir Anda miliki) ...
    const body = document.body;
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
            z-index: 2147483647 !important; /* Max z-index for the portal */
            pointer-events: none !important; /* Portal itself is transparent to clicks */
            overflow: hidden !important; /* To prevent scrollbars on portal */
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            visibility: visible !important;
            opacity: 1 !important;
        `;
        body.appendChild(portalContainer);
        logToDebugDiv('[UltimateFix] Created modal-portal-container and appended to body.', 'info');
    } else {
        portalContainer.innerHTML = '';
        portalContainer.style.setProperty('pointer-events', 'none', 'important');
        portalContainer.style.setProperty('z-index', '2147483647', 'important');
        logToDebugDiv('[UltimateFix] Reusing existing modal-portal-container.', 'info');
    }

    // STEP 3: Clone the modal and move the clone to the portal
    // ... (Bagian ini tetap sama seperti yang terakhir Anda miliki) ...
    const modalClone = modal.cloneNode(true);
    modalClone.id = modal.id + '-portal-clone';
    modalClone.classList.remove('hidden');
    modalClone.classList.add('show');
    modalClone.classList.add('active-portal-modal');

    // Apply nuclear styling to cloned modal
    modalClone.style.cssText = `
        display: flex !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 2147483647 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
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

    // Style the modal content within the cloned modal
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
            z-index: 2147483647 !important;
            pointer-events: auto !important;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
            transform: scale(1) !important;
        `;
    }

    // Append cloned modal to the portal
    portalContainer.appendChild(modalClone);
    logToDebugDiv(`[UltimateFix] Cloned modal ${modal.id} appended to portal.`, 'info');

    // Hide the original modal to prevent duplicates
    modal.style.display = 'none';
    modal.classList.remove('show');
    modal.classList.add('hidden');
    logToDebugDiv(`[UltimateFix] Original modal ${modal.id} hidden.`, 'info');

    // Setup close handlers for the cloned modal
    // Pass scrollPosition to close handler
    setupModalCloseHandlers(modalClone, modal, originalParentStyles, scrollPosition);

    logToDebugDiv('[UltimateFix] Nuclear modal display complete. Modal should now be visible and interactive.', 'success');

    // Force focus to modal for accessibility after a slight delay
    setTimeout(() => {
        modalClone.focus();
        if (modalContent) modalContent.focus();
    }, 100);

    return true;
}

// Global functions for direct access in Blade templates
window.showModal = ultimateModalFix; // Make showModal directly call ultimateModalFix
window.hideModal = closeAllAdminModals; // Use existing closeAllAdminModals as hideModal
window.setupOrderAction = setupAdminOrderAction; // Use setupAdminOrderAction for button logic
window.setupAdminOrderAction = setupAdminOrderAction; // Also expose with the same name for direct use

// Function to handle order actions (confirm payment, process, ship, cancel, complete)
function setupAdminOrderAction(actionType, orderId) {
    logToDebugDiv(`[OrderAction] Setting up order action: ${actionType} for order ${orderId}`, 'action');

    const orderActionModal = document.getElementById('orderActionModal');
    if (!orderActionModal) {
        logToDebugDiv('[OrderAction] Error: orderActionModal element not found!', 'error');
        return;
    }

    const actionForm = document.getElementById('orderActionForm');
    const actionText = document.getElementById('orderActionText');
    const actionButton = document.getElementById('orderActionButton');
    const cancelReasonContainer = document.getElementById('orderCancelReasonContainer');
    const shippingContainer = document.getElementById('orderShippingContainer');
    const modalLabel = document.getElementById('orderActionModalLabel');

    if (!actionForm || !actionText || !actionButton) {
        logToDebugDiv('[OrderAction] Error: Required elements within orderActionModal not found!', 'error');
        return;
    }

    // Reset form and containers
    actionForm.reset();
    if (cancelReasonContainer) cancelReasonContainer.classList.add('hidden');
    if (shippingContainer) shippingContainer.classList.add('hidden');

    // Configure based on action type
    switch (actionType) {
        case 'confirm-payment':
            actionForm.action = `/admin/pesanan/${orderId}/confirm-payment`;
            modalLabel.textContent = 'Konfirmasi Pembayaran';
            actionText.textContent = 'Apakah Anda yakin ingin mengkonfirmasi pembayaran pesanan ini?';
            actionButton.textContent = 'Konfirmasi Pembayaran';
            actionButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm';
            break;

        case 'process-order':
            actionForm.action = `/admin/pesanan/${orderId}/process`;
            modalLabel.textContent = 'Proses Pesanan';
            actionText.textContent = 'Apakah Anda yakin ingin memproses pesanan ini?';
            actionButton.textContent = 'Proses Pesanan';
            actionButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm';
            break;

        case 'ship-order':
            actionForm.action = `/admin/pesanan/${orderId}/ship`;
            modalLabel.textContent = 'Kirim Pesanan';
            actionText.textContent = 'Masukkan nomor resi pengiriman untuk pesanan ini:';
            actionButton.textContent = 'Kirim Pesanan';
            actionButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm';
            if (shippingContainer) shippingContainer.classList.remove('hidden');
            break;

        case 'cancel-order':
            actionForm.action = `/admin/pesanan/${orderId}/cancel`;
            modalLabel.textContent = 'Batalkan Pesanan';
            actionText.textContent = 'Apakah Anda yakin ingin membatalkan pesanan ini?';
            actionButton.textContent = 'Batalkan Pesanan';
            actionButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm';
            if (cancelReasonContainer) cancelReasonContainer.classList.remove('hidden');
            break;

        case 'complete-order':
            actionForm.action = `/admin/pesanan/${orderId}/complete`;
            modalLabel.textContent = 'Selesaikan Pesanan';
            actionText.textContent = 'Apakah Anda yakin ingin menandai pesanan ini sebagai selesai?';
            actionButton.textContent = 'Selesaikan Pesanan';
            actionButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm';
            break;

        default:
            logToDebugDiv(`[OrderAction] Error: Unknown action type: ${actionType}`, 'error');
            return;
    }

    // Show the modal
    logToDebugDiv(`[OrderAction] Opening modal for ${actionType}`, 'info');
    ultimateModalFix('orderActionModal');
}

// Global variable to store scroll position
let scrollPosition = 0;

// Setup order action modal (remains mostly the same as your provided `setupOrderAction` logic)
function setupModalCloseHandlers(clonedModal, originalModal, originalParentStyles, savedScrollPosition) {
    const closeHandler = function(e) {
        // Only close if click is on backdrop or a close button/element
        if (e.target === clonedModal || e.target.closest('.modal-close, [data-dismiss="modal"]')) {
            e.preventDefault();
            e.stopPropagation();

            if (originalParentStyles && originalParentStyles.length > 0) {
                originalParentStyles.forEach(styleInfo => {
                    const { element, overflow, transform, clip, clipPath } = styleInfo;
                    if (element) {
                        element.style.overflow = overflow || '';
                        element.style.transform = transform || '';
                        element.style.clip = clip || '';
                        element.style.clipPath = clipPath || '';
                    }
                });
            }
            logToDebugDiv('[UltimateFix] Restored original parent styles.', 'info');

            // Remove the cloned modal and the portal container
            const portalContainer = document.getElementById('modal-portal-container');
            if (portalContainer) {
                portalContainer.remove();
                logToDebugDiv('[UltimateFix] Removed modal-portal-container.', 'info');
            }

            // Re-hide the original modal (it was display: none)
            originalModal.style.display = 'none';
            originalModal.classList.remove('show');
            originalModal.classList.add('hidden');
            logToDebugDiv(`[UltimateFix] Original modal ${originalModal.id} re-hidden.`, 'info');

            // --- Restore body scroll position ---
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('position');
            document.body.style.removeProperty('top');
            document.body.style.removeProperty('left');
            document.body.style.removeProperty('right');
            window.scrollTo(0, savedScrollPosition); // Scroll back to where user was
            logToDebugDiv(`[UltimateFix] Body scroll restored to position: ${savedScrollPosition}.`, 'success');

            // Clean up this event listener
            clonedModal.removeEventListener('click', closeHandler);
            document.removeEventListener('keydown', escapeHandler);
        }
    };

    clonedModal.addEventListener('click', closeHandler);
    const closeButtons = clonedModal.querySelectorAll('.modal-close, [data-dismiss="modal"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeHandler);
    });

    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            logToDebugDiv('[UltimateFix] Escape key pressed. Closing modal.', 'action');
            closeHandler(e);
        }
    };
    document.addEventListener('keydown', escapeHandler);
}

// Function to copy Resi Number
function copyResi() {
    const resiNumberInput = document.getElementById('resiNumber');
    if (resiNumberInput) {
        resiNumberInput.select();
        resiNumberInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        alert('Nomor Resi berhasil disalin: ' + resiNumberInput.value);
        logToDebugDiv(`[ModalJS] Nomor Resi disalin: ${resiNumberInput.value}`, 'success');
    }
}
window.copyResi = copyResi;

// Function to set up close handlers for the cloned/ported modal
function setupModalCloseHandlers(clonedModal, originalModal, originalParentStyles) {
    // This handler will be specific to the cloned modal, so it should remove itself
    const closeHandler = function(e) {
        // Only close if click is on backdrop or a close button/element
        if (e.target === clonedModal || e.target.closest('.modal-close, [data-dismiss="modal"]')) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling further

            // Restore original parent styles if any were modified
            if (originalParentStyles && originalParentStyles.length > 0) {
                originalParentStyles.forEach(styleInfo => {
                    const { element, overflow, transform, clip, clipPath } = styleInfo;
                    if (element) {
                        element.style.overflow = overflow || ''; // Restore original or unset
                        element.style.transform = transform || '';
                        element.style.clip = clip || '';
                        element.style.clipPath = clipPath || '';
                    }
                });
            }
            logToDebugDiv('[UltimateFix] Restored original parent styles.', 'info');


            // Remove the cloned modal and the portal container
            const portalContainer = document.getElementById('modal-portal-container');
            if (portalContainer) {
                portalContainer.remove();
                logToDebugDiv('[UltimateFix] Removed modal-portal-container.', 'info');
            }

            // Re-hide the original modal (it was display: none)
            originalModal.style.display = 'none';
            originalModal.classList.remove('show');
            originalModal.classList.add('hidden');
            logToDebugDiv(`[UltimateFix] Original modal ${originalModal.id} re-hidden.`, 'info');

            // Restore body scroll
            document.body.style.overflow = '';
            logToDebugDiv('[UltimateFix] Body scroll re-enabled.', 'success');

            // Clean up this event listener
            clonedModal.removeEventListener('click', closeHandler);
            document.removeEventListener('keydown', escapeHandler);
        }
    };

    // Close on backdrop click (applied to the cloned modal itself, which acts as backdrop)
    clonedModal.addEventListener('click', closeHandler);

    // Close on close buttons inside the cloned modal
    const closeButtons = clonedModal.querySelectorAll('.modal-close, [data-dismiss="modal"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeHandler);
    });

    // Close on escape key
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            logToDebugDiv('[UltimateFix] Escape key pressed. Closing modal.', 'action');
            closeHandler(e); // Trigger the same close logic
        }
    };
    document.addEventListener('keydown', escapeHandler);
}

// --- Main Event Listener Setup ---
document.addEventListener('DOMContentLoaded', function() {
    logToDebugDiv('[ModalJS] DOMContentLoaded event fired. Attaching event listeners.', 'info');

    // --- Debug Log Initialization ---
    const debugLogDiv = document.getElementById('debugLog');
    const clearLogBtn = document.getElementById('clearLog');

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

    // Check if the new modal system is active to prevent conflicts
    // This is the primary guard against old scripts.
    if (window.modalSystemInitialized) {
        logToDebugDiv('[ModalJS] Modal system already initialized by this script, skipping re-initialization.', 'warning');
        return;
    }
    window.modalSystemInitialized = true; // Mark as initialized

    // Small delay to ensure all DOM is settled, especially for cloning
    setTimeout(function() {
        logToDebugDiv('[ModalJS] Setting up modal event handlers with a slight delay...', 'info');

        // --- Attach event listeners to Order Action Buttons ---
        const orderActionButtons = document.querySelectorAll('.confirm-payment, .process-order, .ship-order, .cancel-order, .complete-order');
        orderActionButtons.forEach(button => {
            // Remove existing onclick attribute to prevent double-firing or conflicts
            if (button.hasAttribute('onclick')) {
                logToDebugDiv(`Removing redundant 'onclick' attribute from action button: ${button.textContent.trim()}`, 'warning');
                button.removeAttribute('onclick');
            }

            button.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation(); // Stop propagation to prevent parent elements from interfering

                const actionType = this.classList.contains('confirm-payment') ? 'confirm-payment' :
                                 this.classList.contains('process-order') ? 'process-order' :
                                 this.classList.contains('ship-order') ? 'ship-order' :
                                 this.classList.contains('cancel-order') ? 'cancel-order' :
                                 this.classList.contains('complete-order') ? 'complete-order' : null;

                const orderId = this.dataset.id;

                logToDebugDiv(`Action button clicked: Type=${actionType}, OrderID=${orderId}`, 'action');
                if (actionType && orderId) {
                    setupAdminOrderAction(actionType, orderId); // Call the new setup function
                } else {
                    logToDebugDiv('Error: Could not determine actionType or orderId from button.', 'error');
                }
            });
            logToDebugDiv(`Attached listener to: ${button.textContent.trim()} (data-id: ${button.dataset.id})`, 'info');
        });

        // --- Attach event listener for "Lihat Bukti Pembayaran" button ---
        const paymentProofButton = document.querySelector('.trigger-payment-proof-modal');
        if (paymentProofButton) {
            if (paymentProofButton.hasAttribute('onclick')) {
                logToDebugDiv("Removing redundant 'onclick' attribute from 'Lihat Bukti Pembayaran' button.", 'warning');
                paymentProofButton.removeAttribute('onclick');
            }
            paymentProofButton.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                logToDebugDiv('Lihat Bukti Pembayaran button clicked. Showing paymentProofModal.', 'action');
                ultimateModalFix('paymentProofModal'); // Directly call ultimateModalFix
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
                copyResi();
            });
            logToDebugDiv("Attached listener to 'Copy Resi' button.", 'info');
        }

        // Note: Modal close buttons are now handled by setupModalCloseHandlers
        // within ultimateModalFix and closeAllAdminModals, so no direct listeners here.

        logToDebugDiv('All primary event listeners attached.', 'success');
        setupFormSubmissionDebugging(); // Setup form debugging after all listeners are set
    }, 100); // Small delay to ensure all DOM is loaded

    logToDebugDiv('[ModalJS] Admin modal system initialized successfully.', 'success');
});

// Diagnostic Functions (keep as is)
window.diagnoseAdminModalSystem = function() {
    console.clear();
    logToDebugDiv('--- ADMIN MODAL SYSTEM DIAGNOSTIC ---', 'info');
    logToDebugDiv(`Function showModal exists: ${typeof window.showModal === 'function'}`, 'info');
    logToDebugDiv(`Function hideModal exists: ${typeof window.hideModal === 'function'}`, 'info');
    logToDebugDiv(`Function setupOrderAction exists: ${typeof window.setupOrderAction === 'function'}`, 'info');
    logToDebugDiv(`Function copyResi exists: ${typeof window.copyResi === 'function'}`, 'info');
    logToDebugDiv(`Function ultimateModalFix exists: ${typeof window.ultimateModalFix === 'function'}`, 'info');


    const modals = {
        orderAction: document.getElementById('orderActionModal'),
        paymentProof: document.getElementById('paymentProofModal'),
        portalContainer: document.getElementById('modal-portal-container')
    };

    logToDebugDiv('\n--- Modal Elements Status ---', 'info');
    for (const key in modals) {
        const modal = modals[key];
        if (modal) {
            logToDebugDiv(`Modal ${key} (${modal.id}):`, 'info');
            logToDebugDiv(`  - Display: ${getComputedStyle(modal).display}`, 'info');
            logToDebugDiv(`  - Visibility: ${getComputedStyle(modal).visibility}`, 'info');
            logToDebugDiv(`  - Opacity: ${getComputedStyle(modal).opacity}`, 'info');
            logToDebugDiv(`  - z-index: ${getComputedStyle(modal).zIndex}`, 'info');
            logToDebugDiv(`  - pointer-events: ${getComputedStyle(modal).pointerEvents}`, 'info');
            logToDebugDiv(`  - ClassList: ${Array.from(modal.classList).join(', ')}`, 'info');
            if (modal.id === 'orderActionModal') {
                const form = modal.querySelector('form');
                logToDebugDiv(`  - Form Action: ${form ? form.action : 'N/A'}`, 'info');
                logToDebugDiv(`  - Form Method: ${form ? form.method : 'N/A'}`, 'info');
            } else if (modal.id === 'paymentProofModal') {
                const img = modal.querySelector('#paymentProofImage');
                logToDebugDiv(`  - Image src: ${img ? img.src : 'N/A'}`, 'info');
                logToDebugDiv(`  - Image onerror: ${img && img.onerror ? 'Present' : 'N/A'}`, 'info');
            }
        } else {
            logToDebugDiv(`Modal ${key} not found.`, 'warning');
        }
    }

    logToDebugDiv('\n--- Action Buttons Status ---', 'info');
    const buttons = document.querySelectorAll('.confirm-payment, .process-order, .ship-order, .cancel-order, .complete-order, .trigger-payment-proof-modal, .copy-resi-button');
    if (buttons.length > 0) {
        buttons.forEach((btn, i) => {
            logToDebugDiv(`Button ${i + 1} (${btn.textContent.trim()}):`, 'info');
            logToDebugDiv(`  - Classes: ${Array.from(btn.classList).join(', ')}`, 'info');
            logToDebugDiv(`  - data-id: ${btn.dataset.id || 'N/A'}`, 'info');
        });
    } else {
        logToDebugDiv('No action buttons found.', 'warning');
    }

    logToDebugDiv('\n--- End Diagnostic ---', 'info');
};

// Form submission debugging function (called from DOMContentLoaded)
function setupFormSubmissionDebugging() {
    const orderActionForm = document.getElementById('orderActionForm');
    if (orderActionForm) {
        orderActionForm.addEventListener('submit', function(e) {
            logToDebugDiv('[ModalJS] ================================', 'info');
            logToDebugDiv('[ModalJS] FORM SUBMISSION EVENT TRIGGERED', 'info');
            logToDebugDiv('[ModalJS] ================================', 'info');
            logToDebugDiv(`[ModalJS] Form action: ${this.action}`, 'info');
            logToDebugDiv(`[ModalJS] Form method: ${this.method}`, 'info');
            logToDebugDiv(`[ModalJS] Current URL: ${window.location.href}`, 'info');
            logToDebugDiv(`[ModalJS] Form element: ${this}`, 'info');

            const formData = new FormData(this);
            logToDebugDiv('[ModalJS] Form data:', 'info');
            for (let [key, value] of formData.entries()) {
                logToDebugDiv(`  ${key}: ${value}`, 'info');
            }

            if (!this.action || this.action.includes('undefined') || this.action === window.location.href) {
                e.preventDefault();
                logToDebugDiv('❌ FORM ACTION ERROR - Form action is not properly set: ' + this.action, 'error');
                alert('Error: Form action not properly set. Action: ' + this.action);
                return false;
            }

            const actionPath = this.action.replace(window.location.origin, '');
            const validActions = ['/confirm-payment', '/process', '/ship', '/cancel', '/complete'];
            const hasValidAction = validActions.some(action => actionPath.includes(action));

            if (!hasValidAction) {
                e.preventDefault();
                logToDebugDiv('❌ FORM ACTION ERROR - Form action does not contain valid action route: ' + actionPath, 'error');
                alert('Error: Form action does not contain a valid action route. Action: ' + actionPath);
                return false;
            }

            logToDebugDiv('✅ Form validation passed, submitting to: ' + this.action, 'success');
        });
        logToDebugDiv('[ModalJS] Form submission event listener attached to orderActionForm', 'info');
    } else {
        logToDebugDiv('[ModalJS] orderActionForm not found - form submission debugging not available', 'warning');
    }
}
