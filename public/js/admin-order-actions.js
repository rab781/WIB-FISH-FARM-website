/**
 * Admin Order Actions JS Helper
 * Script untuk memperbaiki masalah tombol tindakan admin di halaman detail pesanan
 */

(function() {
    console.log('Admin Order Actions Helper loaded');

    // Debugging log to ensure the file is loaded
    console.log('admin-order-actions.js loaded');

    const actionButtonsDefinition = {
        '.confirm-payment': 'Konfirmasi Pembayaran',
        '.process-order': 'Proses Pesanan',
        '.ship-order': 'Kirim Pesanan',
        '.cancel-order': 'Batalkan Pesanan'
    };

    document.addEventListener('DOMContentLoaded', () => {
        console.log('Document loaded - Initializing order actions');
        initOrderActions();
    });

    function initOrderActions() {
        console.log('Initializing Order Action buttons...');

        // 1. Fix tombol lihat bukti pembayaran
        const paymentProofBtns = document.querySelectorAll('.btnLihatBuktiPembayaran');
        if (paymentProofBtns.length > 0) {
            console.log(`Found ${paymentProofBtns.length} payment proof buttons`);
            paymentProofBtns.forEach(btn => {
                // Hapus event listener yang mungkin sudah ada
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);

                // Tambahkan event listener baru
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Payment proof button clicked');

                    const paymentProofModal = document.getElementById('paymentProofModal');
                    if (!paymentProofModal) {
                        console.error('Payment proof modal not found');
                        alert('Modal bukti pembayaran tidak ditemukan');
                        return;
                    }

                    // Buka modal dengan fungsi standar
                    showModalStandard(paymentProofModal);
                });

                // Tambahkan style khusus untuk memastikan tombol terlihat jelas
                newBtn.style.cursor = 'pointer';
                newBtn.style.position = 'relative';
                newBtn.style.zIndex = '10';
            });
        } else {
            console.log('No payment proof buttons found');
        }

        // 2. Fix tombol tindakan admin utama
        // Tambahkan log tambahan untuk debugging
        Object.keys(actionButtonsDefinition).forEach(selector => {
            const buttons = document.querySelectorAll(selector);

            if (buttons.length > 0) {
                console.log(`Found ${buttons.length} ${actionButtonsDefinition[selector]} buttons`);

                buttons.forEach(btn => {
                    // Hapus event listener yang mungkin sudah ada untuk prevent multiple attachments
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);

                    // Tambahkan event listener baru
                    newBtn.addEventListener('click', handleActionButtonClick);

                    console.log(`Event listener added to ${actionButtonsDefinition[selector]} button with ID: ${newBtn.dataset.id}`);
                });
            } else {
                console.warn(`No ${actionButtonsDefinition[selector]} buttons found`);
            }
        });

        // Pastikan tombol close modal berfungsi
        document.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', function() {
                closeAllModals();
            });
        });

        // Event listener untuk ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });

        console.log('Order action buttons initialization complete');
    }

    function handleActionButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = e.currentTarget;
        // Use actionButtonsDefinition which is in the outer scope
        const actionTypeClass = Object.keys(actionButtonsDefinition).find(selector => button.matches(selector));

        if (!actionTypeClass) {
            console.error('Unknown action type for button:', button);
            return;
        }

        const actionName = actionButtonsDefinition[actionTypeClass];
        console.log(`Button clicked: ${actionName}`);

        const orderId = button.dataset.id;
        if (!orderId) {
            console.error('Order ID not found on button:', button);
            return;
        }

        console.log(`Order ID: ${orderId}`);

        // Corrected function call: setupModalForAction
        // The last parameter for formAction was also incorrect, it should be based on actionTypeClass (e.g. '.confirm-payment')
        // and then mapped to the URL segment (e.g., 'confirm-payment').
        let formActionSegment = actionTypeClass.substring(1); // Remove the leading dot

        setupModalForAction(formActionSegment, orderId); // Pass the simplified action type
    }

    function setupModalForAction(actionType, orderId) {
        const orderActionModal = document.getElementById('orderActionModal');
        const orderActionModalLabel = document.getElementById('orderActionModalLabel');
        const orderActionText = document.getElementById('orderActionText');
        const orderActionButton = document.getElementById('orderActionButton');
        const orderActionForm = document.getElementById('orderActionForm');
        const orderCancelReasonContainer = document.getElementById('orderCancelReasonContainer');
        const orderShippingContainer = document.getElementById('orderShippingContainer');

        if (!orderActionModal || !orderActionModalLabel || !orderActionText ||
            !orderActionButton || !orderActionForm || !orderCancelReasonContainer ||
            !orderShippingContainer) {
            console.error('Some modal elements not found');
            alert('Ada kesalahan pada modal. Silakan refresh halaman.');
            return;
        }

        // Reset containers
        orderCancelReasonContainer.classList.add('hidden');
        orderShippingContainer.classList.add('hidden');

        // Configure modal based on action type
        let title, text, buttonText, formAction, type = null;

        switch (actionType) { // actionType is now 'confirm-payment', 'process-order', etc.
            case 'confirm-payment':
                title = 'Konfirmasi Pembayaran';
                text = `Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan #${orderId}?`;
                buttonText = 'Konfirmasi Pembayaran';
                formAction = `/admin/pesanan/${orderId}/confirm-payment`;
                break;

            case 'process-order':
                title = 'Proses Pesanan';
                text = `Apakah Anda yakin ingin memproses pesanan #${orderId}?`;
                buttonText = 'Proses Pesanan';
                formAction = `/admin/pesanan/${orderId}/process`;
                break;

            case 'ship-order':
                title = 'Kirim Pesanan';
                text = `Masukkan nomor resi pengiriman untuk pesanan #${orderId}`;
                buttonText = 'Kirim Pesanan';
                formAction = `/admin/pesanan/${orderId}/ship`;
                type = 'shipping';
                break;

            case 'cancel-order':
                title = 'Batalkan Pesanan';
                text = `Apakah Anda yakin ingin membatalkan pesanan #${orderId}?`;
                buttonText = 'Batalkan Pesanan';
                formAction = `/admin/pesanan/${orderId}/cancel`;
                type = 'cancel';
                break;

            default:
                console.error('Unknown action type:', actionType);
                return;
        }

        // Update modal content
        orderActionModalLabel.textContent = title;
        orderActionText.textContent = text;
        orderActionButton.textContent = buttonText;
        orderActionForm.action = formAction;

        // Show specific container based on type
        if (type === 'cancel') {
            orderCancelReasonContainer.classList.remove('hidden');
        } else if (type === 'shipping') {
            orderShippingContainer.classList.remove('hidden');
        }

        // Show modal using our standard function
        console.log('Showing orderActionModal with showModalStandard');
        // Assuming showModalStandard is defined elsewhere and works like forceShowModal for now.
        // If showModalStandard is specific and might be the issue, we might need to inspect it.
        // For now, let's try with forceShowModal to ensure the modal itself can be shown.
        forceShowModal('orderActionModal'); // Using forceShowModal for robust display

        // Attach form submit handler
        orderActionForm.onsubmit = function(e) {
            e.preventDefault();

            // Create form data
            const formData = new FormData(orderActionForm);

            // Submit form via AJAX
            fetch(formAction, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Tindakan berhasil dilakukan');
                    closeAllModals();

                    // Reload page after successful action
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification('Terjadi kesalahan: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                showNotification('Terjadi kesalahan saat memproses permintaan', 'error');
            });
        };
    }

    // Expose our close function to the global scope
    window.closeAllModals = function() {
        // Find all modals by id pattern
        const modals = ['orderActionModal', 'paymentProofModal'];

        console.log('Closing all modals');
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                console.log('Closing modal:', modalId);
                // First remove the show class to trigger any CSS transitions
                modal.classList.remove('show');

                // Then set display and add hidden class after a brief delay
                setTimeout(() => {
                    modal.style.display = 'none';
                    modal.classList.add('hidden');
                    console.log('Modal hidden:', modalId);
                }, 150); // Short delay for animation
            }
        });

        // Restore body scroll
        document.body.style.overflow = '';
    };

    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');

        // Set style based on type
        if (type === 'error') {
            notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md z-[9999] transition-opacity duration-300';
        } else {
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md z-[9999] transition-opacity duration-300';
        }

        notification.textContent = message;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Additional utility function for direct modal access
    window.forceShowModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal with ID "${modalId}" not found`);
            return;
        }

        // Force show the modal
        modal.style.display = 'flex';
        modal.style.opacity = '1';
        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        console.log(`Force showing modal: ${modalId}`);
    };
})();
