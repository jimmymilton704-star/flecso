/* Settings page: vertical tab switching + deep-link via hash */
(function () {
  const tabs = document.querySelectorAll(".tab-item");
  const panels = document.querySelectorAll(".tab-panel");
  function activate(id) {
    tabs.forEach(i => i.classList.toggle("active", i.dataset.tab === id));
    panels.forEach(p => p.classList.toggle("active", p.dataset.panel === id));
  }
  tabs.forEach(ti => ti.addEventListener("click", () => {
    activate(ti.dataset.tab);
    history.replaceState(null, "", "#" + ti.dataset.tab);
  }));
  // Honor hash (e.g., settings.html#subscription) on load
  const initial = location.hash.replace("#","");
  if (initial && document.querySelector(`[data-tab="${initial}"]`)) activate(initial);
})();
