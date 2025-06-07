// Modal Diagnostic Script
// Run this in browser console to identify modal visibility issues

window.diagnosticModal = function() {
    console.log('🔍 Starting comprehensive modal diagnostic...');

    const modal = document.getElementById('orderActionModal');
    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }

    console.log('✅ Modal element found:', modal);

    // Check computed styles
    const computedStyle = getComputedStyle(modal);
    console.log('📊 Computed styles:', {
        display: computedStyle.display,
        visibility: computedStyle.visibility,
        opacity: computedStyle.opacity,
        zIndex: computedStyle.zIndex,
        position: computedStyle.position,
        top: computedStyle.top,
        left: computedStyle.left,
        width: computedStyle.width,
        height: computedStyle.height,
        backgroundColor: computedStyle.backgroundColor,
        transform: computedStyle.transform
    });

    // Check parent elements for overflow hidden or other issues
    let parent = modal.parentElement;
    let parentLevel = 1;
    console.log('🔗 Checking parent elements:');

    while (parent && parentLevel <= 5) {
        const parentStyle = getComputedStyle(parent);
        console.log(`Parent ${parentLevel} (${parent.tagName}${parent.id ? '#' + parent.id : ''}${parent.className ? '.' + parent.className.split(' ').join('.') : ''}):`, {
            display: parentStyle.display,
            visibility: parentStyle.visibility,
            overflow: parentStyle.overflow,
            position: parentStyle.position,
            zIndex: parentStyle.zIndex,
            transform: parentStyle.transform
        });
        parent = parent.parentElement;
        parentLevel++;
    }

    // Check CSS rules applied to modal
    console.log('🎨 Checking CSS rules...');
    const sheets = Array.from(document.styleSheets);
    const modalRules = [];

    sheets.forEach((sheet, sheetIndex) => {
        try {
            const rules = Array.from(sheet.cssRules || sheet.rules || []);
            rules.forEach((rule, ruleIndex) => {
                if (rule.selectorText && rule.selectorText.includes('orderActionModal')) {
                    modalRules.push({
                        sheet: sheet.href || `Internal ${sheetIndex}`,
                        selector: rule.selectorText,
                        rules: rule.cssText
                    });
                }
            });
        } catch (e) {
            console.warn('Cannot access stylesheet:', sheet.href);
        }
    });

    console.log('📝 CSS rules affecting modal:', modalRules);

    // Check all CSS classes on modal
    console.log('🏷️ Modal classes:', Array.from(modal.classList));

    // Check inline styles
    console.log('🎨 Inline styles:', modal.style.cssText);

    // Check modal content
    const modalContent = modal.querySelector('.modal-content, .modal-dialog');
    if (modalContent) {
        const contentStyle = getComputedStyle(modalContent);
        console.log('📦 Modal content styles:', {
            display: contentStyle.display,
            visibility: contentStyle.visibility,
            opacity: contentStyle.opacity,
            transform: contentStyle.transform,
            position: contentStyle.position
        });
    }

    // Check for JavaScript errors or conflicts
    console.log('⚠️ Checking for potential conflicts...');

    // Check if any framework is interfering
    const frameworks = {
        jQuery: typeof $ !== 'undefined',
        Bootstrap: typeof bootstrap !== 'undefined',
        Tailwind: document.querySelector('script[src*="tailwind"]') !== null,
        Alpine: typeof Alpine !== 'undefined',
        Livewire: typeof Livewire !== 'undefined'
    };

    console.log('🔧 Detected frameworks:', frameworks);

    // Force show modal for testing
    console.log('🚀 Attempting to force show modal...');
    modal.style.cssText = '';
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.right = '0';
    modal.style.bottom = '0';
    modal.style.zIndex = '999999';
    modal.style.backgroundColor = 'rgba(255, 0, 0, 0.8)'; // Red background for visibility
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';

    // Check if modal is now visible
    setTimeout(() => {
        const rect = modal.getBoundingClientRect();
        console.log('📐 Modal dimensions after force show:', {
            width: rect.width,
            height: rect.height,
            top: rect.top,
            left: rect.left,
            visible: rect.width > 0 && rect.height > 0
        });

        const finalComputedStyle = getComputedStyle(modal);
        console.log('🔍 Final computed styles:', {
            display: finalComputedStyle.display,
            visibility: finalComputedStyle.visibility,
            opacity: finalComputedStyle.opacity
        });

        if (rect.width > 0 && rect.height > 0) {
            console.log('✅ Modal is now visible! The issue was likely CSS conflicts.');
            alert('Modal diagnostic complete! Check console for details. Modal should now be visible.');
        } else {
            console.log('❌ Modal is still not visible after force show. This indicates a deeper issue.');
            alert('Modal still not visible after diagnostic. Check console for more details.');
        }
    }, 100);
};

// Test the nuclear modal approach
window.testNuclearModal = function() {
    console.log('🚀 Testing nuclear modal approach...');

    if (typeof ultimateModalFix === 'function') {
        ultimateModalFix('orderActionModal');
        console.log('✅ Nuclear modal test executed');
    } else {
        console.error('❌ ultimateModalFix function not found!');
    }
};

// Quick test all modal functions
window.testAllModalFunctions = function() {
    console.log('🧪 Testing all modal functions...');

    const functions = [
        'showAdminModal',
        'setupAdminOrderAction',
        'ultimateModalFix',
        'diagnosticModal'
    ];

    functions.forEach(funcName => {
        if (typeof window[funcName] === 'function') {
            console.log(`✅ ${funcName} - Available`);
        } else {
            console.log(`❌ ${funcName} - Missing`);
        }
    });

    // Test showing modal with nuclear approach
    console.log('\n🚀 Testing nuclear modal display...');
    if (typeof ultimateModalFix === 'function') {
        ultimateModalFix('orderActionModal');
    }
};

// Auto-run diagnostic if modal system is loaded
if (document.readyState === 'complete') {
    console.log('🎯 Page loaded. Type diagnosticModal() in console to run modal diagnostic.');
} else {
    window.addEventListener('load', () => {
        console.log('🎯 Page loaded. Type diagnosticModal() in console to run modal diagnostic.');
    });
}
