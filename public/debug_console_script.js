// Script untuk debug order action buttons di console browser
// Paste script ini di Console DevTools pada halaman order detail

console.log('=== ORDER ACTION BUTTONS DEBUG SCRIPT ===');

// 1. Check if elements exist
console.log('\n1. CHECKING DOM ELEMENTS:');
const orderActionModal = document.getElementById('orderActionModal');
const paymentProofModal = document.getElementById('paymentProofModal');

console.log('orderActionModal:', !!orderActionModal, orderActionModal);
console.log('paymentProofModal:', !!paymentProofModal, paymentProofModal);

// 2. Check action buttons
console.log('\n2. CHECKING ACTION BUTTONS:');
const buttons = {
    'confirm-payment': document.querySelectorAll('.confirm-payment'),
    'process-order': document.querySelectorAll('.process-order'),
    'ship-order': document.querySelectorAll('.ship-order'),
    'cancel-order': document.querySelectorAll('.cancel-order'),
    'btnLihatBuktiPembayaran': document.querySelectorAll('.btnLihatBuktiPembayaran')
};

Object.entries(buttons).forEach(([name, elements]) => {
    console.log(`${name}: ${elements.length} found`, elements);
    elements.forEach((el, index) => {
        console.log(`  [${index}] data-id: ${el.dataset.id}, visible: ${el.offsetParent !== null}`);
    });
});

// 3. Check jQuery event listeners
console.log('\n3. CHECKING JQUERY:');
console.log('jQuery loaded:', typeof $ !== 'undefined');
if (typeof $ !== 'undefined') {
    console.log('jQuery version:', $.fn.jquery);
}

// 4. Test click events manually
console.log('\n4. TESTING CLICK EVENTS:');
function testClickEvent(selector) {
    const elements = document.querySelectorAll(selector);
    if (elements.length > 0) {
        console.log(`Testing ${selector}...`);
        elements[0].click();
        console.log(`Clicked ${selector} - check for console output`);
    } else {
        console.log(`No elements found for ${selector}`);
    }
}

// 5. Add temporary click listeners for testing
console.log('\n5. ADDING TEMPORARY CLICK LISTENERS:');
document.addEventListener('click', function(e) {
    if (e.target.matches('.confirm-payment, .process-order, .ship-order, .cancel-order, .btnLihatBuktiPembayaran')) {
        console.log('TEMP LISTENER: Button clicked!', {
            className: e.target.className,
            dataset: e.target.dataset,
            text: e.target.textContent.trim()
        });
    }
});

// 6. Show all dropdown menus (Alpine.js)
console.log('\n6. CHECKING DROPDOWN VISIBILITY:');
const dropdowns = document.querySelectorAll('[x-data*="open"]');
console.log('Alpine dropdowns found:', dropdowns.length);

// Force show all dropdowns to check buttons inside
dropdowns.forEach((dropdown, index) => {
    console.log(`Dropdown ${index}:`, dropdown);
    // Try to trigger Alpine's open state
    if (dropdown.__x) {
        dropdown.__x.$data.open = true;
        console.log(`Forced dropdown ${index} to open`);
    }
});

// 7. Manual test functions
window.debugOrderButtons = {
    showModal: function() {
        if (orderActionModal) {
            orderActionModal.style.display = 'flex';
            orderActionModal.classList.remove('hidden');
            console.log('Modal shown manually');
        }
    },
    hideModal: function() {
        if (orderActionModal) {
            orderActionModal.style.display = 'none';
            orderActionModal.classList.add('hidden');
            console.log('Modal hidden manually');
        }
    },
    testButton: function(selector) {
        testClickEvent(selector);
    },
    showAllButtons: function() {
        // Force show all conditional elements
        const hiddenElements = document.querySelectorAll('[style*="display: none"], .hidden');
        hiddenElements.forEach(el => {
            if (el.matches('.confirm-payment, .process-order, .ship-order, .cancel-order')) {
                el.style.display = 'block';
                el.classList.remove('hidden');
                console.log('Forced show button:', el.className);
            }
        });
    }
};

console.log('\n=== DEBUG FUNCTIONS AVAILABLE ===');
console.log('debugOrderButtons.showModal() - Show modal manually');
console.log('debugOrderButtons.hideModal() - Hide modal manually');
console.log('debugOrderButtons.testButton(selector) - Test specific button');
console.log('debugOrderButtons.showAllButtons() - Force show all hidden buttons');

console.log('\n=== READY FOR TESTING ===');
console.log('Try clicking the action buttons now, or use the debug functions above.');
