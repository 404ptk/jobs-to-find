window.activeFormId = null;

window.openModal = function(modalName, formId = null) {
    const modal = document.getElementById(modalName);
    const content = document.getElementById(`${modalName}-content`);
    const confirmBtn = document.getElementById(`${modalName}-confirm-btn`);
    
    if (!modal) return;
    
    window.activeFormId = formId;
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    if (confirmBtn) {
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        
        newBtn.addEventListener('click', function() {
            if (window.activeFormId) {
                const form = document.getElementById(window.activeFormId);
                if(form) form.submit();
            }
        });
    }
}

window.closeModal = function(modalName) {
    const modal = document.getElementById(modalName);
    const content = document.getElementById(`${modalName}-content`);
    
    if (!modal) return;
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        window.activeFormId = null;
    }, 200);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('div[id^="modal-"]:not(.hidden)');
        openModals.forEach(modal => closeModal(modal.id));
    }
});
