    </div><!-- /.admin-content -->
</div><!-- /.admin-wrapper -->

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
// ── Sidebar Toggle (Mobile) ─────────────────────────────────
const toggle  = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('sidebarOverlay');

if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('show');
    });
}
if (overlay) {
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
}

// ── Delete Confirmation ──────────────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm(this.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    });
});

// ── Image Preview on File Input ──────────────────────────────
document.querySelectorAll('input[data-preview]').forEach(input => {
    input.addEventListener('change', function() {
        const previewId = this.getAttribute('data-preview');
        const preview = document.getElementById(previewId);
        if (!preview) return;
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});

// ── Responsive sidebar toggle button visibility ──────────────
function checkSidebarToggle() {
    if (toggle) {
        toggle.style.display = window.innerWidth <= 768 ? 'block' : 'none';
    }
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('show');
    }
}
checkSidebarToggle();
window.addEventListener('resize', checkSidebarToggle);
</script>
</body>
</html>
