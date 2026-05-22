// ── NAV TOGGLE ──
function toggleNav() {
  document.getElementById('navLinks').classList.toggle('open');
}
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.nav-links a').forEach(a => {
    a.addEventListener('click', () =>
      document.getElementById('navLinks').classList.remove('open')
    );
  });

  // ── SCROLL REVEAL ──
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // ── ACTIVE NAV ──
  const sections = document.querySelectorAll('section[id]');
  const navAs    = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 100) current = s.id; });
    navAs.forEach(a => {
      const href = a.getAttribute('href') || '';
      a.classList.toggle('active', href === '#' + current || href.endsWith('#' + current));
    });
  }, { passive: true });
});

// ── COPY TEXT ──
function copyText(text) {
  navigator.clipboard.writeText(text).then(() => showToast('✅ Copiado!'));
}

// ── TOAST ──
function showToast(msg, duration = 2200) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

// ── MODAL ──
function openModal(id) {
  const m = document.getElementById('modal-' + id);
  if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
  const m = document.getElementById('modal-' + id);
  if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
    document.body.style.overflow = '';
  }
});

// ── LIGHTBOX ──
function openLightbox(src) {
  const lb = document.getElementById('lightbox');
  if (!lb) return;
  lb.querySelector('img').src = src;
  lb.classList.add('open');
}
function closeLightbox() {
  const lb = document.getElementById('lightbox');
  if (lb) lb.classList.remove('open');
}
