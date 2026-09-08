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
