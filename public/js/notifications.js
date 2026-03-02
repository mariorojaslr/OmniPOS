function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = message;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

function showModal(title, message) {
    const modal = document.getElementById('modal-notification');
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-message').innerText = message;
    modal.classList.remove('hidden');
}