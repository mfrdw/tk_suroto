<script>
(() => {
    const root = document.querySelector('.belanja-page');
    if (!root) return;
    root.addEventListener('click', (event) => {
        const button = event.target.closest('[data-qty-step]');
        if (!button || !root.contains(button)) return;
        const input = button.closest('form').querySelector('input[name="qty"]');
        const min = Number(input.min || 1);
        const max = input.max ? Number(input.max) : Infinity;
        const current = Number.parseInt(input.value, 10);
        input.value = Math.min(max, Math.max(min, (Number.isFinite(current) ? current : min) + Number(button.dataset.qtyStep)));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    root.addEventListener('submit', (event) => {
        const message = event.target.dataset.confirm;
        if (message && !window.confirm(message)) event.preventDefault();
    });
})();
</script>
