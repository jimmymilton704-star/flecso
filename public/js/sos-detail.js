/* SOS Detail page */
(function () {
  const D = window.FlecsoData;
  const { icon, toast } = window.Flecso;
  const id = new URLSearchParams(location.search).get("id") || (D.sosAlerts[0] && D.sosAlerts[0].id);
  const s = D.sosAlerts.find(x => x.id === id) || D.sosAlerts[0];
  const root = document.getElementById("detailRoot");
  if (!s) { root.innerHTML = `<p class="muted">SOS alert not found. <a href="sos.html">Back to list</a></p>`; return; }
  document.title = `${s.id} · SOS — Flecso`;

  const driver = D.drivers.find(d => d.id === s.driverId) || D.drivers.find(d => d.name === s.driver);
  const truck  = D.trucks.find(x => x.id === s.truck);

  const severityLabel = {
    critical: { label: "Critical", cls: "sos-banner", icon: "bell" },
    warning:  { label: "Warning",  cls: "sos-banner warn", icon: "bell" },
    info:     { label: "Info",     cls: "sos-banner info", icon: "bell" },
  }[s.severity] || { label: "Alert", cls: "sos-banner", icon: "bell" };

  const banner = s.status === "resolved"
    ? { cls: "sos-banner resolved", label: "Resolved", icon: "check" }
    : severityLabel;

  root.innerHTML = `
    <a href="sos.html" class="detail-back">${icon("trip",14).replace('width="18" height="18"','width="14" height="14"')} Back to SOS Alerts</a>

    <div class="${banner.cls}">
      <div class="sos-banner__icon">${icon(banner.icon, 18)}</div>
      <div>
        <div style="font-size:15px">${banner.label} · ${s.type}</div>
        <div style="font-size:12.5px;font-weight:500;opacity:.85">${s.description}</div>
      </div>
      ${s.status !== "resolved" && s.etaResponse !== "N/A"
        ? `<div class="sos-banner__eta">ETA ${s.etaResponse}</div>`
        : s.status === "resolved" ? `<div class="sos-banner__eta">CLOSED</div>` : ""}
    </div>

    ${s.status !== "resolved" ? `
      <div class="sos-respond-bar">
        <div class="sos-respond-bar__msg">
          <strong>${s.responder}</strong> is currently ${s.status === "acknowledged" ? "responding" : "en route"}.
          ${s.etaResponse !== "N/A" ? ` ETA <strong>${s.etaResponse}</strong>.` : ""}
        </div>
        <button class="btn btn--ghost btn--sm" id="sosAck">${icon("check",14)} Acknowledge</button>
        <button class="btn btn--dark btn--sm" id="sosCall">${icon("phone",14)} Call driver</button>
        <button class="btn btn--primary btn--sm" id="sosResolve">${icon("check",14)} Mark resolved</button>
      </div>` : ""}

    <div class="detail-hero">
      <div class="detail-hero__icon" style="background:linear-gradient(135deg,var(--danger-50),#FFDCDC);color:var(--danger-700)">
        ${icon("bell", 48)}
      </div>
      <div class="detail-hero__body">
        <div class="detail-hero__meta">
          <span class="detail-hero__id">SOS ID</span>
          ${s.severity === "critical" ? `<span class="badge badge--danger"><span class="badge-dot"></span>Critical</span>` :
            s.severity === "warning"  ? `<span class="badge badge--warn"><span class="badge-dot"></span>Warning</span>` :
                                         `<span class="badge badge--info"><span class="badge-dot"></span>Info</span>`}
          <span class="badge badge--neutral">${s.status.charAt(0).toUpperCase() + s.status.slice(1)}</span>
        </div>
        <h1>${s.id}</h1>
        <div class="detail-hero__sub">
          <span>${icon("user",14)} ${s.driver}</span>
          <span>${icon("truck",14)} ${s.truck}</span>
          <span>${icon("map",14)} ${s.location}</span>
          <span>${icon("cal",14)} ${s.raisedAt}</span>
        </div>
      </div>
    </div>

    <div class="detail-quickstats">
      <div class="qs"><div class="qs__label">${icon("bell",12)} Severity</div><div class="qs__value" style="font-size:18px">${s.severity.charAt(0).toUpperCase() + s.severity.slice(1)}</div><div class="qs__sub">${s.type}</div></div>
      <div class="qs"><div class="qs__label">${icon("cal",12)} Time Since Raised</div><div class="qs__value" style="font-size:18px">${s.time}</div><div class="qs__sub">Raised at ${s.raisedAt}</div></div>
      <div class="qs"><div class="qs__label">${icon("spark",12)} Last Speed</div><div class="qs__value" style="font-size:18px">${s.lastSpeed}</div><div class="qs__sub">Telemetry</div></div>
      <div class="qs"><div class="qs__label">${icon("map",12)} Weather</div><div class="qs__value" style="font-size:15px">${s.weather}</div><div class="qs__sub">At incident location</div></div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="card"><div class="card__head"><div class="card__title"><h3>Incident Location</h3></div><span class="badge badge--neutral">${s.location}</span></div>
          <div class="card__body" style="padding:0"><div class="detail-map" id="sosMap" style="height:320px"></div></div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Incident Details</h3></div></div>
          <div class="card__body"><div class="info-grid">
            <div class="info-row"><span class="info-row__key">Incident Type</span><span class="info-row__val">${s.type}</span></div>
            <div class="info-row"><span class="info-row__key">Triggered By</span><span class="info-row__val">${s.triggered}</span></div>
            <div class="info-row"><span class="info-row__key">Raised At</span><span class="info-row__val">${s.raisedAt}</span></div>
            <div class="info-row"><span class="info-row__key">Injuries Reported</span><span class="info-row__val">${s.injuries}</span></div>
            <div class="info-row"><span class="info-row__key">Last Known Speed</span><span class="info-row__val">${s.lastSpeed}</span></div>
            <div class="info-row"><span class="info-row__key">Weather</span><span class="info-row__val">${s.weather}</span></div>
            <div class="info-row"><span class="info-row__key">Assigned Responder</span><span class="info-row__val">${s.responder}</span></div>
            <div class="info-row"><span class="info-row__key">Response ETA</span><span class="info-row__val">${s.etaResponse}</span></div>
          </div>
          <div style="padding:14px;margin-top:14px;background:var(--surface-2);border-radius:12px">
            <h5 style="font-size:13px;margin-bottom:6px">Driver statement</h5>
            <p style="font-size:13px;color:var(--ink-700);line-height:1.55">${s.description}</p>
          </div>
          </div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Response Timeline</h3></div></div>
          <div class="card__body detail-timeline">
            <div class="dtl-item done">
              <div class="dtl-dot">${icon("check",10)}</div>
              <div><div class="dtl-title">SOS alert raised</div><div class="dtl-sub">${s.triggered}</div></div>
              <div class="dtl-time">${s.raisedAt.split(" ")[1] || s.raisedAt}</div>
            </div>
            <div class="dtl-item done">
              <div class="dtl-dot">${icon("check",10)}</div>
              <div><div class="dtl-title">Dispatch notified</div><div class="dtl-sub">Automatic escalation · ${s.responder}</div></div>
              <div class="dtl-time">+0m 14s</div>
            </div>
            <div class="dtl-item ${s.status === "acknowledged" || s.status === "responding" || s.status === "resolved" ? "done" : ""}">
              <div class="dtl-dot">${s.status === "acknowledged" || s.status === "responding" || s.status === "resolved" ? icon("check",10) : ""}</div>
              <div><div class="dtl-title">Alert acknowledged</div><div class="dtl-sub">${s.responder} confirmed receipt</div></div>
              <div class="dtl-time">+1m 32s</div>
            </div>
            <div class="dtl-item ${s.status === "responding" ? "active" : s.status === "resolved" ? "done" : ""}">
              <div class="dtl-dot">${s.status === "resolved" ? icon("check",10) : ""}</div>
              <div><div class="dtl-title">Response team en route</div><div class="dtl-sub">${s.etaResponse !== "N/A" ? "ETA " + s.etaResponse : "Handled remotely"}</div></div>
              <div class="dtl-time">${s.status === "responding" ? "Now" : s.status === "resolved" ? "+3m 08s" : "Pending"}</div>
            </div>
            <div class="dtl-item ${s.status === "resolved" ? "done" : ""}">
              <div class="dtl-dot">${s.status === "resolved" ? icon("check",10) : ""}</div>
              <div><div class="dtl-title">${s.status === "resolved" ? "Incident resolved" : "Incident resolution"}</div><div class="dtl-sub">${s.status === "resolved" ? "All clear — report filed" : "Awaiting resolution"}</div></div>
              <div class="dtl-time">${s.status === "resolved" ? "Closed" : "Pending"}</div>
            </div>
          </div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Operator Notes</h3></div><button class="btn btn--sm btn--primary" id="addNote">${icon("plus",14)} Add note</button></div>
          <div class="card__body">
            <div class="sos-notes" id="notesList">
              <div class="sos-note">
                <img src="https://i.pravatar.cc/80?img=47" alt="">
                <div>
                  <div class="sos-note__head"><strong>Marco Bianchi</strong><span>${s.raisedAt.split(" ")[1] || s.raisedAt}</span></div>
                  <p>Alert received. Dispatching ${s.responder}. Attempting two-way voice check with driver.</p>
                </div>
              </div>
              <div class="sos-note">
                <img src="https://i.pravatar.cc/80?img=32" alt="">
                <div>
                  <div class="sos-note__head"><strong>Giulia Operatore</strong><span>+1m 32s</span></div>
                  <p>${s.driver} confirmed status via radio. ${s.injuries === "None" ? "No injuries reported." : "Minor injuries noted — medical response triggered."} Coordinating with ${s.responder}.</p>
                </div>
              </div>
              ${s.status === "resolved" ? `
              <div class="sos-note">
                <img src="https://i.pravatar.cc/80?img=15" alt="">
                <div>
                  <div class="sos-note__head"><strong>Stefano Rossi</strong><span>Closed</span></div>
                  <p>Incident closed. Report filed under case ${s.id}-R. Driver safe, cargo secure, equipment inspected.</p>
                </div>
              </div>` : ""}
            </div>
          </div>
        </div>
      </div>

      <aside class="detail-side">
        ${driver ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Driver</h3></div></div>
            <div class="card__body">
              <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                <img src="${driver.avatar}" alt="">
                <div style="flex:1">
                  <div class="assignee__name">${driver.name}</div>
                  <div class="assignee__sub">${driver.phone}</div>
                </div>
              </div>
              <a href="driver-detail.html?id=${driver.id}" class="btn btn--sm btn--ghost btn--block">View profile</a>
            </div>
          </div>` : ""}

        ${truck ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Vehicle</h3></div></div>
            <div class="card__body">
              <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                <img src="${truck.image}" style="width:48px;height:48px;border-radius:10px;object-fit:cover" alt="">
                <div style="flex:1">
                  <div class="assignee__name">${truck.id}</div>
                  <div class="assignee__sub">${truck.plate} · ${truck.category}</div>
                </div>
              </div>
              <a href="truck-detail.html?id=${truck.id}" class="btn btn--sm btn--ghost btn--block">View truck</a>
            </div>
          </div>` : ""}

        ${s.trip && s.trip !== "—" ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Active Trip</h3></div></div>
            <div class="card__body">
              <div class="info-row" style="padding-top:0"><span class="info-row__key">Trip ID</span><span class="info-row__val"><a href="trip-detail.html?id=${s.trip}" style="color:var(--orange-600)">${s.trip}</a></span></div>
              <a href="trip-detail.html?id=${s.trip}" class="btn btn--sm btn--ghost btn--block" style="margin-top:8px">View trip</a>
            </div>
          </div>` : ""}

        <div class="card"><div class="card__head"><div class="card__title"><h3>Emergency Actions</h3></div></div>
          <div class="card__body side-actions">
            <button class="side-action">${icon("phone",14)} Call driver</button>
            <button class="side-action">${icon("phone",14)} Call responder</button>
            <button class="side-action">${icon("mail",14).replace('<path d="M1','<path d="M1')} Notify emergency services</button>
            <button class="side-action">${icon("doc",14)} Print incident report</button>
            <button class="side-action">${icon("upload",14)} Attach evidence</button>
            ${s.status !== "resolved" ? `<button class="side-action danger">${icon("check",14)} Mark resolved</button>` : `<button class="side-action">${icon("edit",14)} Amend report</button>`}
          </div>
        </div>
      </aside>
    </div>
  `;

  // Map
  if (window.L) {
    const map = L.map("sosMap", { zoomControl: true, attributionControl: false }).setView(s.coords, 11);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { subdomains: "abcd" }).addTo(map);
    const cls = s.status === "resolved" ? "info" : (s.severity === "warning" ? "warn" : (s.severity === "info" ? "info" : ""));
    const html = `<div class="sos-marker ${cls}" style="width:38px;height:38px"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0ZM12 9v4M12 17h.01"/></svg></div>`;
    L.marker(s.coords, { icon: L.divIcon({ className:"", html, iconSize:[38,38], iconAnchor:[19,19] }) })
      .addTo(map)
      .bindPopup(`<strong>${s.id}</strong><br>${s.location}`);
  }

  // Respond-bar actions
  document.getElementById("sosAck")?.addEventListener("click", () => toast("Alert acknowledged · responder notified"));
  document.getElementById("sosCall")?.addEventListener("click", () => toast(`Dialing ${s.driver}…`));
  document.getElementById("sosResolve")?.addEventListener("click", () => {
    if (confirm("Mark this SOS alert as resolved? A closing report will be generated.")) {
      toast(`${s.id} marked as resolved`);
      setTimeout(() => location.href = "sos.html", 700);
    }
  });
  document.getElementById("addNote")?.addEventListener("click", () => {
    const note = prompt("Add a note to this incident:");
    if (!note) return;
    const list = document.getElementById("notesList");
    const html = `
      <div class="sos-note">
        <img src="https://i.pravatar.cc/80?img=47" alt="">
        <div>
          <div class="sos-note__head"><strong>Marco Bianchi</strong><span>Just now</span></div>
          <p>${note.replace(/</g,"&lt;")}</p>
        </div>
      </div>`;
    list.insertAdjacentHTML("beforeend", html);
    toast("Note added to incident");
  });
})();
