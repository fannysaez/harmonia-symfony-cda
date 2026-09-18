// Compteurs animés
document.querySelectorAll('[data-counter]').forEach(el => {
    const target = parseInt(el.dataset.counter);
    const start = performance.now();
    const duration = 900;
    (function step(now) {
        const p = Math.min((now - start) / duration, 1);
        el.textContent = Math.floor(p * p * target);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target;
    })(start);
});

// Salutation dynamique — nom récupéré depuis data-name
const greetingEl = document.getElementById('greeting');
const name = greetingEl.dataset.name;
const h = new Date().getHours();
const greet = h < 12 ? 'Bonjour' : h < 18 ? 'Bon après-midi' : 'Bonsoir';
greetingEl.textContent = greet + ', ' + name + ' !';

// Date en français
const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
document.getElementById('current-date').textContent =
    new Date().toLocaleDateString('fr-FR', opts);
