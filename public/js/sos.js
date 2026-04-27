/* SOS Alerts listing page */
(function () {
  const D = window.FlecsoData;
  const { icon } = window.Flecso;

  const sevBadge = (sev, status) => {
    if (status === "resolved") return `<span class="badge badge--success"><span class="badge-dot"></span>Resolved</span>`;
    if (status === "acknowledged") return `<span class="badge badge--info"><span class="badge-dot"></span>Acknowledged</span>`;
    if (sev === "critical") return `<span class="badge badge--danger"><span class="badge-dot"></span>Critical</span>`;
    if (sev === "warning")  return `<span class="badge badge--warn"><span class="badge-dot"></span>Warning</span>`;
    return `<span class="badge badge--info"><span class="badge-dot"></span>Info</span>`;
  };

  /* Top stats */
  const critical = D.sosAlerts.filter(s => s.severity === "critical" && s.status !== "resolved").length;
  const warning  = D.sosAlerts.filter(s => s.severity === "warning"  && s.status !== "resolved").length;
  const resolved = D.sosAlerts.filter(s => s.status === "resolved").length;
  document.getElementById("statCritical").textContent = critical;
  document.getElementById("statWarning").textContent  = warning;
  document.getElementById("statResolved").textContent = resolved;

  /* Incident types breakdown */
  const types = {};
  D.sosAlerts.forEach(s => {
    const key = s.type.split(" · ")[0];
    types[key] = (types[key] || 0) + 1;
  });
  const typeColors = { "Accident":"#EF4444", "Mechanical":"#F59E0B", "Medical":"#FF6B1A", "Customs":"#3B82F6", "Theft":"#111114", "Weather":"#64748B", "Tyre":"#FFB84D", "Fuel":"#10B981" };
  const typesEl = document.getElementById("sosTypes");
  if (typesEl) {
    const max = Math.max(...Object.values(types));
    typesEl.innerHTML = Object.entries(types)
      .sort((a,b) => b[1] - a[1])
      .map(([k, v]) => `
        <div class="sos-type">
          <span class="sos-type__dot" style="background:${typeColors[k] || "#FF6B1A"}"></span>
          <span class="sos-type__label">${k}</span>
          <span class="sos-type__count">${v}</span>
          <div class="sos-type__bar"><span style="width:${(v/max)*100}%;background:${typeColors[k] || "#FF6B1A"}"></span></div>
        </div>`).join("");
  }

  /* Table rows */
  const tbody = document.getElementById("sosBody");
  if (tbody) {
    tbody.innerHTML = D.sosAlerts.map(s => `
      <tr onclick="location.href='sos-detail.html?id=${s.id}'" style="cursor:pointer">
        <td>
          <div class="asset-name">${s.id}</div>
          <div class="asset-sub">${s.triggered}</div>
        </td>
        <td>${s.type}</td>
        <td>
          <strong>${s.driver}</strong>
          <div class="asset-sub">${s.truck}${s.trip && s.trip !== "—" ? " · " + s.trip : ""}</div>
        </td>
        <td>${s.location}</td>
        <td>${s.time}</td>
        <td>${
          s.severity === "critical" ? `<span class="badge badge--danger"><span class="badge-dot"></span>Critical</span>` :
          s.severity === "warning"  ? `<span class="badge badge--warn"><span class="badge-dot"></span>Warning</span>` :
                                       `<span class="badge badge--info"><span class="badge-dot"></span>Info</span>`
        }</td>
        <td>${sevBadge(s.severity, s.status)}</td>
        <td onclick="event.stopPropagation()">
          <div class="row-actions">
            <a href="sos-detail.html?id=${s.id}" class="mini-btn" title="View">${icon("eye",14)}</a>
            ${s.status !== "resolved" ? `<button class="btn btn--sm btn--dark" onclick="location.href='sos-detail.html?id=${s.id}'">Respond</button>` : ""}
          </div>
        </td>
      </tr>`).join("");
  }

  document.getElementById("sosCount").textContent = `Showing 1–${D.sosAlerts.length} of ${D.sosAlerts.length} alerts`;

  /* Map */
  const mapEl = document.getElementById("sosMap");
  if (mapEl && window.L) {
    const map = L.map(mapEl, { zoomControl: true, attributionControl: false }).setView([45.5, 11.2], 5);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { subdomains: "abcd" }).addTo(map);

    D.sosAlerts.forEach(s => {
      const cls = s.status === "resolved" ? "info" : (s.severity === "warning" ? "warn" : (s.severity === "info" ? "info" : ""));
      const html = `<div class="sos-marker ${cls}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0ZM12 9v4M12 17h.01"/></svg></div>`;
      L.marker(s.coords, { icon: L.divIcon({ className:"", html, iconSize:[28,28], iconAnchor:[14,14] }) })
        .addTo(map)
        .bindPopup(`
          <div style="font-family:Inter;font-size:12.5px;min-width:220px">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:4px">
              <strong style="font-family:'Space Grotesk';font-size:13px">${s.id}</strong>
              <span style="font-size:11px;color:#737373">${s.time}</span>
            </div>
            <div><strong>${s.driver}</strong> · ${s.truck}</div>
            <div style="color:#5B5B63">${s.type}</div>
            <div style="margin-top:6px"><a href="sos-detail.html?id=${s.id}" style="color:#FF6B1A;font-weight:600">View details →</a></div>
          </div>
        `);
    });
  }
})();
