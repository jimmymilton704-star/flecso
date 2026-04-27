/* Dashboard: SOS alerts, trip chart, live map, activity feed, schedule */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge } = window.Flecso;

  /* ---------- SOS alerts ---------- */
  const sosEl = document.getElementById("sosList");
  if (sosEl) {
    sosEl.innerHTML = D.sosAlerts.filter(s => s.status !== "resolved").slice(0, 3).map(s => `
      <div class="sos-item" onclick="location.href='sos-detail.html?id=${s.id}'" style="cursor:pointer">
        <span class="sos-dot"></span>
        <div>
          <h5>${s.id} · ${s.driver}</h5>
          <p>${s.location} · ${s.time}</p>
        </div>
        <a href="sos-detail.html?id=${s.id}" class="btn btn--sm btn--dark" onclick="event.stopPropagation()">Respond</a>
      </div>`).join("");
  }

  // Wire dashboard SOS "View all" link
  document.querySelectorAll(".card__head .btn--sm.btn--ghost").forEach(b => {
    if (b.textContent.trim() === "View all" && b.closest(".card__head")?.querySelector("h3")?.textContent === "SOS Alerts") {
      b.addEventListener("click", e => { e.stopPropagation(); location.href = "sos.html"; }, { capture: true });
    }
  });

  /* ---------- Activity ---------- */
  const activityEl = document.getElementById("activityList");
  if (activityEl) {
    const tones = { orange:"background:var(--orange-50);color:var(--orange-700)", dark:"background:var(--ink-900);color:#fff", green:"background:var(--success-50);color:var(--success-700)", blue:"background:var(--info-50);color:var(--info-500)" };
    activityEl.innerHTML = D.activity.map(a => `
      <div class="activity-item">
        <div class="activity-avatar" style="${tones[a.tone]}">${icon(a.icon,18)}</div>
        <div><h5>${a.title}</h5><p>${a.sub}</p></div>
        <span class="activity-time">${a.time}</span>
      </div>`).join("");
  }

  /* ---------- Schedule ---------- */
  const schEl = document.getElementById("scheduleList");
  if (schEl) {
    schEl.innerHTML = D.schedule.map(s => `
      <div class="schedule-row">
        <div class="schedule-date"><strong>${s.day}</strong><small>${s.mo}</small></div>
        <div>
          <div class="schedule-title">${s.title}</div>
          <div class="schedule-sub">${s.sub}</div>
        </div>
        ${statusBadge(s.status === "success" ? "completed" : s.status === "warn" ? "delayed" : "active")}
      </div>`).join("");
  }

  /* ---------- Trip activity chart ---------- */
  const ctx = document.getElementById("tripChart");
  if (ctx && window.Chart) {
    const ds = (color, data, fill = false) => ({
      data, tension:.4, borderColor: color, backgroundColor: fill ? color + "22" : "transparent",
      fill, pointRadius: 0, pointHoverRadius: 5, pointBackgroundColor: color, borderWidth: 2.5,
    });
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
        datasets: [
          ds("#10B981", [32,38,29,42,37,28,44], true),
          ds("#FF6B1A", [18,22,25,21,28,24,26], true),
          ds("#F59E0B", [4,3,5,6,4,7,5]),
          ds("#EF4444", [1,2,1,3,1,2,2]),
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { backgroundColor:"#111114", padding: 10, cornerRadius: 8 } },
        scales: {
          x: { grid: { display: false }, ticks: { color:"#8C8C95", font:{size:11,weight:500} } },
          y: { beginAtZero: true, grid: { color:"#ECECEF" }, ticks: { color:"#8C8C95", font:{size:11,weight:500}, stepSize: 10 }, border: { display: false } },
        },
      }
    });
  }

  /* ---------- Live fleet map ---------- */
  const mapEl = document.getElementById("mapEl");
  if (mapEl && window.L) {
    const map = L.map(mapEl, { zoomControl: true, attributionControl: false }).setView([45.6, 10.5], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { subdomains: 'abcd', maxZoom: 19 }).addTo(map);
    const iconHtml = `<div class="truck-marker">${icon("truck",16)}</div>`;
    const divIcon = L.divIcon({ className: "", html: iconHtml, iconSize:[36,36], iconAnchor:[18,18] });
    D.liveTrucks.forEach(t => {
      L.marker([t.lat, t.lng], { icon: divIcon }).addTo(map)
        .bindPopup(`<div style="font-family:Inter;font-size:12px"><strong style="font-family:'Space Grotesk';font-size:13px">${t.id}</strong><br>${t.name}<br><span style="color:#5B5B63">Speed: ${t.speed} km/h · ${t.heading}</span></div>`);
    });
  }
})();
