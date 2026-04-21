// ============================================================
// PORTAL INFORMASI KELURAHAN PULOMERAK — MAIN JS
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ── STICKY NAVBAR ──────────────────────────────────────
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // ── MOBILE NAV TOGGLE ──────────────────────────────────
    const navToggle  = document.getElementById('navToggle');
    const navMenu    = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');

    if (navToggle && navMenu) {
        function toggleMenu() {
            const isOpen = navMenu.classList.toggle('open');
            navToggle.classList.toggle('open');
            if (navOverlay) navOverlay.classList.toggle('show');
            
            // Lock body and html scroll
            const scrollStyle = isOpen ? 'hidden' : '';
            document.body.style.overflow = scrollStyle;
            document.documentElement.style.overflow = scrollStyle;
        }

        navToggle.addEventListener('click', toggleMenu);

        // Close on overlay or outside click
        document.addEventListener('click', function (e) {
            if (navMenu.classList.contains('open')) {
                if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                    toggleMenu();
                }
            }
        });
        
        if (navOverlay) {
            navOverlay.addEventListener('click', toggleMenu);
        }
    }

    // ── BACK TO TOP ────────────────────────────────────────
    const btn = document.getElementById('backToTop');
    if (btn) {
        window.addEventListener('scroll', function () {
            btn.classList.toggle('show', window.scrollY > 400);
        });
        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ── SCROLL REVEAL ──────────────────────────────────────
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(function (el) { observer.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('visible'); });
    }

    // ── COUNTER ANIMATION ──────────────────────────────────
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const cObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    cObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(function (el) { cObs.observe(el); });
    }
    function animateCounter(el) {
        const target   = parseInt(el.dataset.count);
        const duration = 1800;
        const start    = performance.now();
        function update(time) {
            const progress = Math.min((time - start) / duration, 1);
            const eased    = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(eased * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    }

    // ── KEGIATAN FILTER ────────────────────────────────────
    const filterBtns  = document.querySelectorAll('[data-filter]');
    const filterItems = document.querySelectorAll('[data-category]');
    if (filterBtns.length && filterItems.length) {
        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                const filter = btn.dataset.filter;
                filterItems.forEach(function (item) {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.style.display = '';
                        item.classList.add('reveal');
                        setTimeout(function () { item.classList.add('visible'); }, 50);
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('visible');
                    }
                });
            });
        });
    }

    // ── LIGHTBOX (GALERI) ──────────────────────────────────
    const galeriImgs = document.querySelectorAll('.galeri-item[data-src]');
    if (galeriImgs.length) {
        // Create lightbox
        const lb = document.createElement('div');
        lb.id = 'lightbox';
        lb.style.cssText = `
            display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(0,0,0,0.92); align-items:center; justify-content:center;
            cursor:zoom-out;
        `;
        lb.innerHTML = `
            <div style="max-width:90vw;max-height:90vh;position:relative;">
                <img id="lbImg" src="" alt="" style="max-width:90vw;max-height:85vh;border-radius:12px;display:block;">
                <div id="lbCaption" style="color:#fff;text-align:center;margin-top:12px;font-size:0.9rem;opacity:0.85;"></div>
                <button id="lbClose" style="position:absolute;top:-16px;right:-16px;width:36px;height:36px;
                    background:#fff;border-radius:50%;font-size:1.2rem;color:#333;border:none;cursor:pointer;">✕</button>
            </div>`;
        document.body.appendChild(lb);

        galeriImgs.forEach(function (item) {
            item.addEventListener('click', function () {
                const src     = item.dataset.src;
                const caption = item.dataset.caption || '';
                document.getElementById('lbImg').src = src;
                document.getElementById('lbCaption').textContent = caption;
                lb.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        lb.addEventListener('click', function (e) {
            if (e.target === lb || e.target.id === 'lbClose') {
                lb.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && lb.style.display === 'flex') {
                lb.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }

    // ── AUTO DISMISS ALERTS ────────────────────────────────
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4000);
    });

    // ── SEARCH LIVE (Sederhana) ────────────────────────────
    const searchInput = document.getElementById('liveSearch');
    if (searchInput) {
        const items = document.querySelectorAll('.searchable-item');
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            items.forEach(function (item) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    // ── DELETE CONFIRM ─────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            const msg = el.dataset.confirm || 'Apakah Anda yakin ingin menghapus data ini?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── ADMIN SIDEBAR TOGGLE (mobile) ─────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar  = document.querySelector('.admin-sidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            adminSidebar.classList.toggle('open');
        });
    }

    // ── IMAGE PREVIEW (upload) ─────────────────────────────
    const imgInputs = document.querySelectorAll('[data-preview]');
    imgInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview   = document.getElementById(previewId);
            if (!preview) return;
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // ── TOAST SYSTEM ──────────────────────────────────────
    window.showToast = function(msg, type = 'info') {
        const toastContainer = document.getElementById('toastContainer') || (function() {
            const c = document.createElement('div');
            c.id = 'toastContainer';
            c.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10000;display:flex;flex-direction:column;gap:10px;pointer-events:none;';
            document.body.appendChild(c);
            return c;
        })();

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
        toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> <span>${msg}</span>`;
        toastContainer.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    };

    // ── REALTIME ENGINE ────────────────────────────────────
    function initRealtime() {
        const isAdmin = window.location.pathname.includes('/admin/');
        if (!isAdmin) return;

        const pollInterval = 20000; // 20 detik
        let lastBeritaId   = localStorage.getItem('last_berita_id');
        let lastKegiatanId = localStorage.getItem('last_kegiatan_id');

        async function poll() {
            try {
                const res  = await fetch('../admin/api-admin.php');
                const data = await res.json();
                
                if (data.status === 'success') {
                    // Update stats if on dashboard
                    const statNums = document.querySelectorAll('.stat-num');
                    if (statNums.length > 0) {
                        if(document.querySelector('.stat-label + .stat-num') === null) {
                            // Map stats to UI if possible (simple version)
                            // This depends on the order, we could add IDs to stat-nums
                        }
                    }

                    // Check for new content
                    if (lastBeritaId && data.latest.berita && data.latest.berita.id > lastBeritaId) {
                        showToast(`Halo ka, ada berita baru nih: ${data.latest.berita.judul}`, 'success');
                    }
                    if (lastKegiatanId && data.latest.kegiatan && data.latest.kegiatan.id > lastKegiatanId) {
                        showToast(`Ada info kegiatan baru lho: ${data.latest.kegiatan.judul}`, 'info');
                    }

                    // Update last seen
                    if (data.latest.berita) localStorage.setItem('last_berita_id', data.latest.berita.id);
                    if (data.latest.kegiatan) localStorage.setItem('last_kegiatan_id', data.latest.kegiatan.id);
                    lastBeritaId = localStorage.getItem('last_berita_id');
                    lastKegiatanId = localStorage.getItem('last_kegiatan_id');
                }
            } catch (e) { console.warn('Polling failed', e); }
        }

        // Jalankan poll pertama dan set interval
        setTimeout(poll, 2000); // Tunggu sebentar setelah load
        setInterval(poll, pollInterval);
    }

    initRealtime();

});
