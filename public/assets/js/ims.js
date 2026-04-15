document.addEventListener('DOMContentLoaded', () => {
    const currentYearTargets = document.querySelectorAll('[data-current-year]');

    currentYearTargets.forEach((target) => {
        target.textContent = new Date().getFullYear().toString();
    });
});
