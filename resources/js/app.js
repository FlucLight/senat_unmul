const sidebar = document.getElementById('sidebar');
const backdrop = document.getElementById('sidebar-backdrop');
const toggle = document.getElementById('sidebar-toggle');

function closeSidebar() {
    if (sidebar) sidebar.classList.add('-translate-x-full');
    if (backdrop) backdrop.classList.add('hidden');
}

if (toggle && sidebar && backdrop) {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    });
    backdrop.addEventListener('click', closeSidebar);
}

document.querySelectorAll('.copy-qr-link').forEach((button) => {
    button.addEventListener('click', async () => {
        const url = button.dataset.copyUrl;
        if (!url) return;

        const originalText = button.innerHTML;
        try {
            await navigator.clipboard.writeText(url);
            button.textContent = 'Tersalin!';
        } catch {
            button.textContent = 'Gagal salin';
        }
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 1600);
    });
});