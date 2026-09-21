document.addEventListener('DOMContentLoaded', function () {
  const animatedItems = document.querySelectorAll('.card-service');

  if (!('IntersectionObserver' in window)) {
    animatedItems.forEach(el => el.classList.add('in-view'));
    return;
  }

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        // 👇 stop observing so it only plays once
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  animatedItems.forEach(el => observer.observe(el));
});
