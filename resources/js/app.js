// Mobile Drawer Navigation (Gaya Moodle)
const drawer = document.getElementById('mobile-drawer');
const drawerBackdrop = document.getElementById('mobile-drawer-backdrop');
const drawerToggle = document.getElementById('mobile-menu-toggle');
const drawerClose = document.getElementById('mobile-drawer-close');

function closeDrawer() {
    if (drawer) drawer.classList.add('-translate-x-full');
    if (drawerBackdrop) drawerBackdrop.classList.add('hidden');
}

function openDrawer() {
    if (drawer) drawer.classList.remove('-translate-x-full');
    if (drawerBackdrop) drawerBackdrop.classList.remove('hidden');
}

if (drawerToggle) drawerToggle.addEventListener('click', openDrawer);
if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);

// Close user dropdown when clicked outside
const userDropdown = document.getElementById('user-menu-dropdown');
if (userDropdown) {
    document.addEventListener('click', (e) => {
        if (!userDropdown.contains(e.target)) {
            userDropdown.removeAttribute('open');
        }
    });
}

// Mobile filter bottom sheet (Daftar Isi)
const filterSheet = document.getElementById('filter-sheet');
const filterToggle = document.getElementById('filter-toggle');
const filterClose = document.getElementById('filter-close');
const filterBackdrop = document.getElementById('filter-backdrop');

function openFilters() {
    if (!filterSheet || !filterBackdrop) return;
    filterSheet.classList.remove('translate-y-full');
    filterBackdrop.classList.remove('hidden');
}

function closeFilters() {
    if (!filterSheet || !filterBackdrop) return;
    filterSheet.classList.add('translate-y-full');
    filterBackdrop.classList.add('hidden');
}

if (filterToggle) filterToggle.addEventListener('click', openFilters);
if (filterClose) filterClose.addEventListener('click', closeFilters);
if (filterBackdrop) filterBackdrop.addEventListener('click', closeFilters);

// Copy QR verification link
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

// Global themed confirmation modal using native <dialog>
(() => {
    const dialog = document.getElementById('app-confirm-dialog');
    if (!dialog) return;

    const titleEl = document.getElementById('app-confirm-title');
    const messageEl = document.getElementById('app-confirm-message');
    const okBtn = document.getElementById('app-confirm-ok');
    const iconWrap = document.getElementById('app-confirm-icon-wrap');
    const iconPrimary = document.getElementById('app-confirm-icon-primary');
    const iconDanger = document.getElementById('app-confirm-icon-danger');

    // Fallback for browsers without <dialog closedby="any">
    if (!('closedBy' in HTMLDialogElement.prototype)) {
        const outside = (e) => {
            const r = dialog.getBoundingClientRect();
            return e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom;
        };
        let pressedOutside = false;
        dialog.addEventListener('pointerdown', (e) => {
            pressedOutside = outside(e);
        });
        dialog.addEventListener('pointerup', (e) => {
            if (pressedOutside && outside(e)) dialog.close();
        });
    }

    let pendingResolve = null;

    dialog.addEventListener('close', () => {
        const confirmed = dialog.returnValue === 'confirm';
        dialog.returnValue = '';
        if (pendingResolve) {
            const resolve = pendingResolve;
            pendingResolve = null;
            resolve(confirmed);
        }
    });

    window.appConfirm = ({
        title = 'Konfirmasi',
        message = 'Lanjutkan tindakan ini?',
        okText = 'Lanjutkan',
        variant = 'primary',
    } = {}) => {
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        if (okBtn) {
            okBtn.textContent = okText;
            okBtn.classList.remove('bg-brand', 'hover:bg-brand-hover', 'active:bg-brand-active', 'bg-danger-700', 'hover:opacity-90');
            if (variant === 'danger') {
                okBtn.classList.add('bg-danger-700', 'hover:opacity-90');
            } else {
                okBtn.classList.add('bg-brand', 'hover:bg-brand-hover', 'active:bg-brand-active');
            }
        }
        if (iconWrap && iconPrimary && iconDanger) {
            iconWrap.classList.toggle('bg-brand-subtle', variant !== 'danger');
            iconWrap.classList.toggle('text-brand', variant !== 'danger');
            iconWrap.classList.toggle('bg-danger-50', variant === 'danger');
            iconWrap.classList.toggle('text-danger-700', variant === 'danger');
            iconPrimary.classList.toggle('hidden', variant === 'danger');
            iconDanger.classList.toggle('hidden', variant !== 'danger');
        }

        return new Promise((resolve) => {
            pendingResolve = resolve;
            dialog.returnValue = '';
            dialog.showModal();
        });
    };

    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('button[data-confirm], input[type="submit"][data-confirm]');
        if (!trigger) return;
        if (trigger.dataset.confirmed === '1') {
            delete trigger.dataset.confirmed;
            return;
        }
        e.preventDefault();
        const ok = await window.appConfirm({
            title: trigger.dataset.confirmTitle || 'Konfirmasi',
            message: trigger.dataset.confirm,
            okText: trigger.dataset.confirmOk || 'Lanjutkan',
            variant: trigger.dataset.confirmVariant || 'primary',
        });
        if (!ok) return;
        trigger.dataset.confirmed = '1';
        if (trigger.form) {
            trigger.form.requestSubmit(trigger);
        } else {
            trigger.click();
        }
    });

    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;
        if (form.dataset.confirmed === '1') {
            delete form.dataset.confirmed;
            return;
        }
        e.preventDefault();
        const ok = await window.appConfirm({
            title: form.dataset.confirmTitle || 'Konfirmasi',
            message: form.dataset.confirm,
            okText: form.dataset.confirmOk || 'Hapus',
            variant: form.dataset.confirmVariant || 'danger',
        });
        if (!ok) return;
        form.dataset.confirmed = '1';
        form.submit();
    });
})();
