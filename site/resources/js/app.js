// Kasawo Yaffe: interactions and motion
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const $ = (s, el = document) => el.querySelector(s);
const $$ = (s, el = document) => [...el.querySelectorAll(s)];

// ---- Header: transparent over heroes, solid on scroll + reading progress
const header = $('.site-header');
const progress = $('.scroll-progress');
const toTop = $('.to-top');
const onScroll = () => {
    const y = window.scrollY;
    header?.classList.toggle('scrolled', y > 40 || $('#navLinks')?.classList.contains('open'));
    toTop?.classList.toggle('show', y > 900);
    if (progress) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = `scaleX(${max > 0 ? y / max : 0})`;
    }
};
window.addEventListener('scroll', onScroll, { passive: true });
onScroll();
toTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// ---- Mobile menu
$('.menu-btn')?.addEventListener('click', () => {
    $('#navLinks')?.classList.toggle('open');
    onScroll();
});

// ---- Count-up numbers
const countUp = (el) => {
    const target = parseFloat(el.dataset.count);
    const suffix = el.dataset.suffix || '';
    if (reduceMotion) { el.textContent = target.toLocaleString() + suffix; return; }
    const start = performance.now();
    const dur = 1600;
    const tick = (now) => {
        const p = Math.min((now - start) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(target * eased).toLocaleString() + suffix;
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
};

// ---- Scroll reveal (+ stagger children with data-stagger)
$$('[data-stagger]').forEach((group) => {
    const step = parseFloat(group.dataset.stagger) || 0.08;
    [...group.children].forEach((child, i) => {
        if (!child.hasAttribute('data-reveal')) child.setAttribute('data-reveal', '');
        child.style.setProperty('--d', `${i * step}s`);
    });
});
const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (!e.isIntersecting) return;
        const el = e.target;
        el.classList.add('is-visible');
        const counters = $$('[data-count]', el);
        if (el.matches('[data-count]')) counters.push(el);
        counters.forEach(countUp);
        $$('.progress > span[data-width]', el).forEach((bar) => { bar.style.width = bar.dataset.width; });
        io.unobserve(el);
    });
}, { threshold: 0, rootMargin: '0px 0px -12% 0px' });
$$('[data-reveal], [data-count], .progress').forEach((el) => io.observe(el));

// ---- Hero slideshow
const slides = $$('.hero-slide');
if (slides.length > 1) {
    const dots = $$('.hero-dots button');
    const credit = $('.photo-credit');
    let i = 0;
    let timer;
    const show = (n) => {
        slides[i].classList.remove('on');
        dots[i]?.classList.remove('on');
        i = (n + slides.length) % slides.length;
        slides[i].classList.add('on');
        dots[i]?.classList.add('on');
        if (credit) credit.textContent = slides[i].dataset.credit || '';
    };
    const play = () => {
        clearInterval(timer);
        if (!reduceMotion) timer = setInterval(() => show(i + 1), 6500);
    };
    dots.forEach((d, n) => d.addEventListener('click', () => { show(n); play(); }));
    play();
}

// ---- Flash toast
const toast = $('.toast');
if (toast) setTimeout(() => toast.classList.add('hide'), 6000);

// ---- Image upload preview
$$('.upload input[type=file]').forEach((input) => {
    input.addEventListener('change', () => {
        const box = input.closest('.upload').querySelector('.upload-preview');
        if (!box) return;
        box.replaceChildren();
        [...input.files].slice(0, 10).forEach((f) => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            box.appendChild(img);
        });
    });
});

// ---- Donation form: live summary
const donate = $('#donate-form');
if (donate) {
    const custom = $('[name=custom_amount]', donate);
    const out = $('#donate-total');
    const freqOut = $('#donate-freq');
    const company = $('#company-field');
    const update = () => {
        const preset = $('[name=amount]:checked', donate);
        let amount = parseInt((custom.value || '').replace(/\D/g, ''), 10);
        if (!amount && preset && preset.value !== 'custom') amount = parseInt(preset.value, 10);
        out.textContent = 'UGX ' + (amount || 0).toLocaleString();
        out.classList.remove('bump');
        void out.offsetWidth;
        out.classList.add('bump');
        freqOut.textContent = $('[name=frequency]:checked', donate)?.value === 'monthly' ? 'every month' : 'one-time gift';
        company.hidden = $('[name=donor_type]:checked', donate)?.value !== 'company';
    };
    custom.addEventListener('focus', () => {
        const c = $('[name=amount][value=custom]', donate);
        if (c) c.checked = true;
        update();
    });
    custom.addEventListener('input', (e) => {
        const n = e.target.value.replace(/\D/g, '');
        e.target.value = n ? parseInt(n, 10).toLocaleString() : '';
        update();
    });
    $$('[name=amount]', donate).forEach((r) => r.addEventListener('change', () => {
        if (r.value !== 'custom') custom.value = '';
        update();
    }));
    $$('[name=frequency], [name=donor_type]', donate).forEach((r) => r.addEventListener('change', update));
    update();
}

// ---- Admin: confirm destructive or money-related actions
document.addEventListener('submit', (e) => {
    const msg = e.target.dataset?.confirm;
    if (msg && !window.confirm(msg)) e.preventDefault();
});

// ---- Admin: chart bars grow in
$$('[data-bars] i[data-h]').forEach((bar) => requestAnimationFrame(() => setTimeout(() => { bar.style.height = bar.dataset.h; }, 150)));

// ---- Admin: live image preview
$$('[data-preview-target]').forEach((input) => {
    input.addEventListener('change', () => {
        const img = $(`[data-preview="${input.dataset.previewTarget}"]`);
        if (img && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            img.style.display = '';
            img.animate([{ opacity: 0, transform: 'scale(.96)' }, { opacity: 1, transform: 'none' }], { duration: 400, easing: 'ease-out' });
        }
    });
});

// ---- Admin: add another product row
$$('[data-add-row]').forEach((btn) => btn.addEventListener('click', () => {
    const box = $(btn.dataset.addRow);
    const last = box.lastElementChild;
    const row = last.cloneNode(true);
    const next = box.children.length;
    row.querySelectorAll('input').forEach((inp) => {
        inp.value = '';
        inp.name = inp.name.replace(/\[\d+\]/, `[${next}]`);
    });
    box.appendChild(row);
    row.animate([{ opacity: 0, transform: 'translateY(-8px)' }, { opacity: 1, transform: 'none' }], { duration: 300 });
    row.querySelector('input').focus();
}));

// ---- Confetti on the thank-you page
if ($('[data-confetti]') && !reduceMotion) {
    const box = document.createElement('div');
    box.className = 'confetti';
    const colors = ['#c99a2e', '#8f4322', '#2c5a3f', '#f3d48a', '#e8c165'];
    for (let n = 0; n < 90; n++) {
        const p = document.createElement('i');
        p.style.left = Math.random() * 100 + 'vw';
        p.style.background = colors[n % colors.length];
        p.style.animationDuration = 2.5 + Math.random() * 2.5 + 's';
        p.style.animationDelay = Math.random() * 0.8 + 's';
        box.appendChild(p);
    }
    document.body.appendChild(box);
    setTimeout(() => { box.hidden = true; }, 6000);
}
