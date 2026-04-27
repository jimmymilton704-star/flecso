/* Truck Detail page — reads ?id=XXX and renders a single truck */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge } = window.Flecso;
  const id = new URLSearchParams(location.search).get("id") || (D.trucks[0] && D.trucks[0].id);
  const t = D.trucks.find(x => x.id === id) || D.trucks[0];
  const root = document.getElementById("detailRoot");
  if (!t) { root.innerHTML = `<p class="muted">Truck not found. <a href="trucks.html">Back to list</a></p>`; return; }

  document.title = `${t.id} · Truck — Flecso`;

  const serviceDueKm = parseInt(String(t.mileage).replace(/\D/g,"")) + 12000;

  root.innerHTML = `
    <a href="trucks.html" class="detail-back">${icon("trip",14).replace('width="18" height="18"','width="14" height="14"')} Back to Trucks</a>

    <div class="detail-hero">
      <img class="detail-hero__img" src="${t.image}" alt="" />
      <div class="detail-hero__body">
        <div class="detail-hero__meta">
          <span class="detail-hero__id">Truck ID</span>
          ${statusBadge(t.status)}
          <span class="badge badge--neutral">${t.category}</span>
          <span class="badge badge--orange">${t.fuel}</span>
        </div>
        <h1>${t.id}</h1>
        <div class="detail-hero__sub">
          <span>${icon("doc",14)} Plate ${t.plate}</span>
          <span>${icon("user",14)} ${t.driver}</span>
          <span>${icon("spark",14)} ${t.capacity} tons capacity</span>
        </div>
      </div>
      <div class="detail-hero__actions">
        <button class="btn btn--ghost" onclick="showQR('Truck ${t.id}','${t.plate}')">${icon("qr",16)} QR Code</button>
        <button class="btn btn--ghost">${icon("edit",16)} Edit</button>
        <button class="btn btn--primary">${icon("route",16)} Assign Trip</button>
      </div>
    </div>

    <div class="detail-quickstats">
      <div class="qs">
        <div class="qs__label">${icon("spark",12)} Capacity</div>
        <div class="qs__value">${t.capacity} t</div>
        <div class="qs__sub">Max payload 18,000 kg</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("route",12)} Mileage</div>
        <div class="qs__value">${t.mileage}</div>
        <div class="qs__sub">+2,410 km this month</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("cal",12)} Last Service</div>
        <div class="qs__value" style="font-size:17px">${t.lastService}</div>
        <div class="qs__sub">At ${parseInt(String(t.mileage).replace(/\D/g,"")) - 8000} km</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("bell",12)} Next Service Due</div>
        <div class="qs__value" style="font-size:17px">In 12,000 km</div>
        <div class="qs__sub">≈ ${serviceDueKm.toLocaleString()} km</div>
      </div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="detail-tabs" role="tablist">
          <button class="active" data-pane="overview">Overview</button>
          <button data-pane="specs">Technical Specs</button>
          <button data-pane="compliance">Compliance</button>
          <button data-pane="maintenance">Maintenance</button>
        </div>

        <div class="detail-pane active" data-pane="overview">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Basic Information</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Truck Number</span><span class="info-row__val">${t.id}</span></div>
              <div class="info-row"><span class="info-row__key">License Plate</span><span class="info-row__val"><code>${t.plate}</code></span></div>
              <div class="info-row"><span class="info-row__key">Category</span><span class="info-row__val">${t.category}</span></div>
              <div class="info-row"><span class="info-row__key">Capacity</span><span class="info-row__val">${t.capacity} tons</span></div>
              <div class="info-row"><span class="info-row__key">Fuel Type</span><span class="info-row__val">${t.fuel}</span></div>
              <div class="info-row"><span class="info-row__key">Status</span><span class="info-row__val">${statusBadge(t.status)}</span></div>
            </div></div>
          </div>
          <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Identity & Legal</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">VIN / Chassis No.</span><span class="info-row__val"><code>WDB9634031L${String(Math.abs(t.id.charCodeAt(4)*1337)).slice(0,6)}</code></span></div>
              <div class="info-row"><span class="info-row__key">Registration Date</span><span class="info-row__val">2022-03-14</span></div>
              <div class="info-row"><span class="info-row__key">Usage Type</span><span class="info-row__val">Owned</span></div>
              <div class="info-row"><span class="info-row__key">Country of Reg.</span><span class="info-row__val">Italy (IT)</span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="specs">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Technical Specifications</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Vehicle Category</span><span class="info-row__val">N3 (Heavy)</span></div>
              <div class="info-row"><span class="info-row__key">Gross Vehicle Weight</span><span class="info-row__val">26,000 kg</span></div>
              <div class="info-row"><span class="info-row__key">Payload Capacity</span><span class="info-row__val">18,000 kg</span></div>
              <div class="info-row"><span class="info-row__key">Number of Axles</span><span class="info-row__val">3</span></div>
              <div class="info-row"><span class="info-row__key">Euro Class</span><span class="info-row__val">Euro VI</span></div>
              <div class="info-row"><span class="info-row__key">Fuel Type</span><span class="info-row__val">${t.fuel}</span></div>
              <div class="info-row"><span class="info-row__key">Transmission</span><span class="info-row__val">Automatic 12-speed</span></div>
              <div class="info-row"><span class="info-row__key">Engine Power</span><span class="info-row__val">480 hp · 353 kW</span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="compliance">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Compliance & Documents</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Last Inspection</span><span class="info-row__val">2026-01-18 <span class="badge badge--success" style="margin-left:6px">Passed</span></span></div>
              <div class="info-row"><span class="info-row__key">Next Inspection</span><span class="info-row__val">2027-01-18</span></div>
              <div class="info-row"><span class="info-row__key">Insurance Provider</span><span class="info-row__val">Generali Italia</span></div>
              <div class="info-row"><span class="info-row__key">Insurance Expiry</span><span class="info-row__val">2026-08-31</span></div>
              <div class="info-row"><span class="info-row__key">Tachograph Expiry</span><span class="info-row__val">2027-04-02</span></div>
              <div class="info-row"><span class="info-row__key">Road Tax Expiry</span><span class="info-row__val">2026-12-31</span></div>
            </div>
            <div style="margin-top:14px"><div class="doc-chips">
              <span class="doc-chip">${icon("doc",12)} Registration.pdf</span>
              <span class="doc-chip">${icon("doc",12)} Insurance-2026.pdf</span>
              <span class="doc-chip">${icon("doc",12)} Road-tax-2026.pdf</span>
              <span class="doc-chip">${icon("doc",12)} Tachograph.pdf</span>
            </div></div>
            </div>
          </div>
        </div>

        <div class="detail-pane" data-pane="maintenance">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Service History</h3></div><button class="btn btn--sm btn--primary">${icon("plus",14)} Log Service</button></div>
            <div class="card__body">
              <div class="info-row"><span class="info-row__key">${t.lastService} — Major service · ${t.driver ? t.driver.split(" ")[0] : "—"}'s garage</span><span class="info-row__val">€ 1,420</span></div>
              <div class="info-row"><span class="info-row__key">2025-10-04 — Tyres rotated</span><span class="info-row__val">€ 220</span></div>
              <div class="info-row"><span class="info-row__key">2025-07-19 — Brake pads replaced</span><span class="info-row__val">€ 640</span></div>
              <div class="info-row"><span class="info-row__key">2025-04-02 — Oil & filter change</span><span class="info-row__val">€ 180</span></div>
              <div class="info-row"><span class="info-row__key">2024-12-14 — Annual inspection</span><span class="info-row__val">€ 260</span></div>
            </div>
          </div>
        </div>
      </div>

      <aside class="detail-side">
        <div class="card side-qr">
          <div class="card__title" style="justify-content:center;margin-bottom:10px"><h3>QR Code</h3></div>
          <div class="side-qr__frame"><div id="sideQr"></div></div>
          <div class="side-qr__code">flecso://truck/${t.id}</div>
          <div class="side-qr__actions">
            <button class="btn btn--sm btn--ghost" onclick="showQR('Truck ${t.id}','${t.plate}')">${icon("eye",14)} View</button>
            <button class="btn btn--sm btn--ghost" id="sideQrDl">${icon("upload",14)} Save</button>
          </div>
        </div>

        <div class="card">
          <div class="card__head"><div class="card__title"><h3>Assigned Driver</h3></div></div>
          <div class="card__body"><div class="assignee" style="background:transparent;padding:0">
            <img src="https://i.pravatar.cc/80?img=${12 + (t.id.charCodeAt(4) % 40)}" alt="">
            <div style="flex:1">
              <div class="assignee__name">${t.driver}</div>
              <div class="assignee__sub">Primary driver · 4.9 ★</div>
            </div>
            <a href="drivers.html" class="btn btn--sm btn--ghost">View</a>
          </div></div>
        </div>

        <div class="card">
          <div class="card__head"><div class="card__title"><h3>Actions</h3></div></div>
          <div class="card__body side-actions">
            <button class="side-action">${icon("route",14)} Assign to a trip</button>
            <button class="side-action">${icon("cal",14)} Schedule service</button>
            <button class="side-action">${icon("doc",14)} Generate report</button>
            <button class="side-action">${icon("upload",14)} Upload documents</button>
            <button class="side-action danger">${icon("trash",14)} Archive truck</button>
          </div>
        </div>
      </aside>
    </div>
  `;

  // Tabs
  document.querySelectorAll(".detail-tabs button").forEach(b =>
    b.addEventListener("click", () => {
      document.querySelectorAll(".detail-tabs button").forEach(x => x.classList.remove("active"));
      b.classList.add("active");
      document.querySelectorAll(".detail-pane").forEach(p => p.classList.toggle("active", p.dataset.pane === b.dataset.pane));
    })
  );

  // Side QR
  if (window.QRCode) {
    new QRCode(document.getElementById("sideQr"), {
      text: `flecso://truck/${t.id}`,
      width: 160, height: 160,
      colorDark: "#0A0A0B", colorLight: "#FFFFFF",
      correctLevel: QRCode.CorrectLevel.H,
    });
  }
  document.getElementById("sideQrDl")?.addEventListener("click", () => {
    const c = document.querySelector("#sideQr canvas");
    if (!c) return;
    const a = document.createElement("a");
    a.download = `${t.id}-qr.png`;
    a.href = c.toDataURL("image/png"); a.click();
  });
})();
