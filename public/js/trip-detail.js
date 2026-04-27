/* Trip Detail page — with route map + timeline */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge } = window.Flecso;
  const id = new URLSearchParams(location.search).get("id") || (D.trips[0] && D.trips[0].id);
  const t = D.trips.find(x => x.id === id) || D.trips[0];
  const root = document.getElementById("detailRoot");
  if (!t) { root.innerHTML = `<p class="muted">Trip not found. <a href="trips.html">Back to list</a></p>`; return; }
  document.title = `${t.id} · Trip — Flecso`;

  const driver = D.drivers.find(d => d.name === t.driver);
  const truck  = D.trucks.find(x => x.id === t.truck);
  const container = D.containers.find(x => x.id === t.container);
  const progress = t.status === "completed" ? 100 : t.status === "cancelled" ? 0 : t.status === "delayed" ? 55 : 72;

  // Rough city coordinates for route preview
  const cities = {
    "Milan, IT":[45.4642,9.19],"Turin, IT":[45.0703,7.6869],"Bologna, IT":[44.4949,11.3426],
    "Munich, DE":[48.1351,11.582],"Rome, IT":[41.9028,12.4964],"Verona, IT":[45.4384,10.9916],
    "Vienna, AT":[48.2082,16.3738],"Naples, IT":[40.8518,14.2681],"Marseille, FR":[43.2965,5.3698],
    "Geneva, CH":[46.2044,6.1432]
  };
  const fromLL = cities[t.from] || [45,10];
  const toLL   = cities[t.to]   || [46,11];

  root.innerHTML = `
    <a href="trips.html" class="detail-back">${icon("trip",14).replace('width="18" height="18"','width="14" height="14"')} Back to Trips</a>

    <div class="detail-hero">
      <div class="detail-hero__icon">${icon("route",48)}</div>
      <div class="detail-hero__body">
        <div class="detail-hero__meta">
          <span class="detail-hero__id">${t.id}</span>
          ${statusBadge(t.status)}
          <span class="badge badge--orange">${t.type}</span>
        </div>
        <h1>${t.from} → ${t.to}</h1>
        <div class="detail-hero__sub">
          <span>${icon("cal",14)} ${t.date}</span>
          <span>${icon("route",14)} ${t.distance} km</span>
          <span>${icon("user",14)} ${t.driver}</span>
        </div>
      </div>
      <div class="detail-hero__actions">
        <button class="btn btn--ghost">${icon("doc",16)} Print BoL</button>
        <button class="btn btn--ghost">${icon("edit",16)} Edit</button>
        ${t.status === "active" || t.status === "delayed" ? `<button class="btn btn--primary">${icon("bell",16)} Notify Driver</button>` : ""}
      </div>
    </div>

    <div class="detail-quickstats">
      <div class="qs"><div class="qs__label">${icon("route",12)} Distance</div><div class="qs__value">${t.distance} km</div><div class="qs__sub">Direct route</div></div>
      <div class="qs"><div class="qs__label">${icon("cal",12)} ETA</div><div class="qs__value" style="font-size:18px">${t.eta || "—"}</div><div class="qs__sub">${t.status === "completed" ? "Delivered on-time" : t.status === "cancelled" ? "Trip cancelled" : "In transit"}</div></div>
      <div class="qs"><div class="qs__label">${icon("check",12)} Progress</div><div class="qs__value">${progress}%</div>
        <div style="height:6px;background:var(--ink-100);border-radius:999px;margin-top:6px;overflow:hidden"><div style="width:${progress}%;height:100%;background:var(--orange-grad)"></div></div>
      </div>
      <div class="qs"><div class="qs__label">${icon("box",12)} Payload</div><div class="qs__value">12.5 t</div><div class="qs__sub">of ${truck ? truck.capacity : 24} t capacity</div></div>
    </div>

    <div class="route-display">
      <div class="route-display__stop">
        <div class="route-display__pin route-display__pin--from">${icon("pin",16).replace(ICON_PIN_ORIG, ICON_PIN_ORIG) || icon("spark",16)}</div>
        <div>
          <div class="route-display__label">Pickup</div>
          <div class="route-display__place">${t.from}</div>
        </div>
      </div>
      <div class="route-display__line"></div>
      <div class="route-display__stop">
        <div class="route-display__pin route-display__pin--to">${icon("check",16)}</div>
        <div>
          <div class="route-display__label">Drop-off</div>
          <div class="route-display__place">${t.to}</div>
        </div>
      </div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="card"><div class="card__head"><div class="card__title"><h3>Route</h3></div><span class="badge badge--neutral">${t.distance} km</span></div>
          <div class="card__body" style="padding:0"><div class="detail-map" id="routeMap"></div></div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Trip Timeline</h3></div></div>
          <div class="card__body detail-timeline">
            <div class="dtl-item done">
              <div class="dtl-dot">${icon("check",10)}</div>
              <div><div class="dtl-title">Trip scheduled</div><div class="dtl-sub">Dispatch created & driver assigned</div></div>
              <div class="dtl-time">Apr 19 · 16:42</div>
            </div>
            <div class="dtl-item done">
              <div class="dtl-dot">${icon("check",10)}</div>
              <div><div class="dtl-title">Vehicle check completed</div><div class="dtl-sub">Pre-trip inspection passed</div></div>
              <div class="dtl-time">Apr 20 · 07:58</div>
            </div>
            <div class="dtl-item done">
              <div class="dtl-dot">${icon("check",10)}</div>
              <div><div class="dtl-title">Picked up from ${t.from}</div><div class="dtl-sub">Container sealed · SEAL-85421</div></div>
              <div class="dtl-time">Apr 20 · 08:30</div>
            </div>
            <div class="dtl-item ${t.status === "completed" ? "done" : "active"}">
              <div class="dtl-dot">${t.status === "completed" ? icon("check",10) : ""}</div>
              <div><div class="dtl-title">${t.status === "completed" ? "Delivered to " + t.to : "En route to " + t.to}</div><div class="dtl-sub">${t.status === "completed" ? "Proof of delivery received" : "Currently " + progress + "% of the way"}</div></div>
              <div class="dtl-time">${t.status === "completed" ? "Apr 20 · 17:14" : "ETA " + (t.eta || "—")}</div>
            </div>
            ${t.status !== "completed" ? `
              <div class="dtl-item">
                <div class="dtl-dot"></div>
                <div><div class="dtl-title">Delivery confirmation</div><div class="dtl-sub">Signature and photo proof</div></div>
                <div class="dtl-time">Pending</div>
              </div>
              <div class="dtl-item">
                <div class="dtl-dot"></div>
                <div><div class="dtl-title">Trip closed & invoiced</div><div class="dtl-sub">Automatic upon delivery confirmation</div></div>
                <div class="dtl-time">Pending</div>
              </div>` : ""}
          </div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Package Details</h3></div></div>
          <div class="card__body"><div class="info-grid">
            <div class="info-row"><span class="info-row__key">Description</span><span class="info-row__val">Palletised automotive parts</span></div>
            <div class="info-row"><span class="info-row__key">Total Weight</span><span class="info-row__val">12,500 kg</span></div>
            <div class="info-row"><span class="info-row__key">Dimensions (H × L × W)</span><span class="info-row__val">8 × 40 × 8 ft</span></div>
            <div class="info-row"><span class="info-row__key">Packages</span><span class="info-row__val">24 pallets</span></div>
            <div class="info-row"><span class="info-row__key">Hazardous</span><span class="info-row__val">No</span></div>
            <div class="info-row"><span class="info-row__key">Temperature Controlled</span><span class="info-row__val">No</span></div>
          </div></div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Delivery Contact</h3></div></div>
          <div class="card__body"><div class="info-grid">
            <div class="info-row"><span class="info-row__key">Contact Name</span><span class="info-row__val">Giulia Ferrari</span></div>
            <div class="info-row"><span class="info-row__key">Email</span><span class="info-row__val">g.ferrari@acme.it</span></div>
            <div class="info-row"><span class="info-row__key">Phone</span><span class="info-row__val">+39 340 112 3344</span></div>
            <div class="info-row"><span class="info-row__key">Delivery Window</span><span class="info-row__val">09:00–17:00</span></div>
          </div></div>
        </div>

        <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Payment</h3></div></div>
          <div class="card__body"><div class="info-grid">
            <div class="info-row"><span class="info-row__key">Payment Account</span><span class="info-row__val">Invoice Account — FC-001</span></div>
            <div class="info-row"><span class="info-row__key">Estimated Cost</span><span class="info-row__val">€ 1,840</span></div>
            <div class="info-row"><span class="info-row__key">Fuel Allowance</span><span class="info-row__val">€ 280</span></div>
            <div class="info-row"><span class="info-row__key">Invoice Status</span><span class="info-row__val">${t.status === "completed" ? `<span class="badge badge--success">Issued</span>` : `<span class="badge badge--neutral">Pending</span>`}</span></div>
          </div></div>
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
                  <div class="assignee__sub">★ ${driver.rating} · ${driver.trips} trips</div>
                </div>
              </div>
              <a href="driver-detail.html?id=${driver.id}" class="btn btn--sm btn--ghost btn--block">View profile</a>
            </div>
          </div>` : ""}

        ${truck ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Truck</h3></div></div>
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

        ${container ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Container</h3></div></div>
            <div class="card__body">
              <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                <img src="${container.image}" style="width:48px;height:48px;border-radius:10px;object-fit:cover" alt="">
                <div style="flex:1">
                  <div class="assignee__name">${container.id}</div>
                  <div class="assignee__sub">${container.type} · ISO ${container.iso}</div>
                </div>
              </div>
              <a href="container-detail.html?id=${container.id}" class="btn btn--sm btn--ghost btn--block">View container</a>
            </div>
          </div>` : ""}

        <div class="card"><div class="card__head"><div class="card__title"><h3>Actions</h3></div></div>
          <div class="card__body side-actions">
            <button class="side-action">${icon("route",14)} Reroute trip</button>
            <button class="side-action">${icon("bell",14)} Notify driver</button>
            <button class="side-action">${icon("doc",14)} Download BoL</button>
            <button class="side-action">${icon("upload",14)} Attach document</button>
            <button class="side-action danger">${icon("trash",14)} Cancel trip</button>
          </div>
        </div>
      </aside>
    </div>
  `;

  // Route map
  if (window.L) {
    const map = L.map("routeMap", { zoomControl: true, attributionControl: false }).setView([ (fromLL[0]+toLL[0])/2, (fromLL[1]+toLL[1])/2 ], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { subdomains: 'abcd' }).addTo(map);

    // Origin marker
    const originIcon = L.divIcon({ className:"", html: `<div style="width:30px;height:30px;border-radius:50%;background:var(--orange-grad,linear-gradient(135deg,#FF8A2B,#FF5500));display:grid;place-items:center;color:#fff;box-shadow:0 4px 12px rgba(255,107,26,.5)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="5" fill="currentColor"/></svg></div>`, iconSize:[30,30], iconAnchor:[15,15] });
    const destIcon = L.divIcon({ className:"", html: `<div style="width:30px;height:30px;border-radius:50%;background:#111114;display:grid;place-items:center;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.3)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>`, iconSize:[30,30], iconAnchor:[15,15] });

    L.marker(fromLL, { icon: originIcon }).addTo(map).bindPopup(`<strong>Pickup</strong><br>${t.from}`);
    L.marker(toLL,   { icon: destIcon   }).addTo(map).bindPopup(`<strong>Drop-off</strong><br>${t.to}`);

    // Completed portion vs remaining
    const mid = [ fromLL[0] + (toLL[0]-fromLL[0])*progress/100, fromLL[1] + (toLL[1]-fromLL[1])*progress/100 ];
    const solid = L.polyline([fromLL, mid], { color:"#FF6B1A", weight: 4, opacity: .95 }).addTo(map);
    const dashed = L.polyline([mid, toLL], { color:"#FF6B1A", weight: 3, opacity: .5, dashArray:"6,8" }).addTo(map);

    if (progress > 0 && progress < 100) {
      const live = L.divIcon({ className:"", html:`<div class="truck-marker" style="width:32px;height:32px"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h10v10H3z"/><path d="M13 10h5l3 3v4h-8"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg></div>`, iconSize:[32,32], iconAnchor:[16,16] });
      L.marker(mid, { icon: live }).addTo(map).bindPopup(`<strong>${t.truck || "Truck"}</strong><br>${progress}% complete`);
    }

    map.fitBounds(L.featureGroup([solid, dashed]).getBounds(), { padding: [30,30] });
  }
})();

// Helper: some icon markers use pin which may have long path; simple fallback
var ICON_PIN_ORIG = "";
