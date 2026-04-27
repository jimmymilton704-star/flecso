/* Container Detail page */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge } = window.Flecso;
  const id = new URLSearchParams(location.search).get("id") || (D.containers[0] && D.containers[0].id);
  const c = D.containers.find(x => x.id === id) || D.containers[0];
  const root = document.getElementById("detailRoot");
  if (!c) { root.innerHTML = `<p class="muted">Container not found. <a href="containers.html">Back to list</a></p>`; return; }
  document.title = `${c.id} · Container — Flecso`;

  root.innerHTML = `
    <a href="containers.html" class="detail-back">${icon("trip",14).replace('width="18" height="18"','width="14" height="14"')} Back to Containers</a>

    <div class="detail-hero">
      <img class="detail-hero__img" src="${c.image}" alt="" />
      <div class="detail-hero__body">
        <div class="detail-hero__meta">
          <span class="detail-hero__id">Container ID</span>
          ${statusBadge(c.status)}
          <span class="badge badge--neutral">${c.type}</span>
          <span class="badge badge--orange">ISO ${c.iso}</span>
        </div>
        <h1>${c.id}</h1>
        <div class="detail-hero__sub">
          <span>${icon("box",14)} ${c.owner} · ${c.serial}-${c.checkDigit}</span>
          <span>${icon("map",14)} ${c.location}</span>
          <span>${icon("spark",14)} ${c.weight} tons</span>
        </div>
      </div>
      <div class="detail-hero__actions">
        <button class="btn btn--ghost" onclick="showQR('Container ${c.id}','ISO ${c.iso}')">${icon("qr",16)} QR Code</button>
        <button class="btn btn--ghost">${icon("edit",16)} Edit</button>
        <button class="btn btn--primary">${icon("route",16)} Assign to Trip</button>
      </div>
    </div>

    <div class="detail-quickstats">
      <div class="qs">
        <div class="qs__label">${icon("spark",12)} Current Weight</div>
        <div class="qs__value">${c.weight} t</div>
        <div class="qs__sub">of max 30.48 t</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("box",12)} Type</div>
        <div class="qs__value" style="font-size:17px">${c.type}</div>
        <div class="qs__sub">ISO ${c.iso}</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("map",12)} Location</div>
        <div class="qs__value" style="font-size:17px">${c.location}</div>
        <div class="qs__sub">Last updated 12 min ago</div>
      </div>
      <div class="qs">
        <div class="qs__label">${icon("cal",12)} Last Inspection</div>
        <div class="qs__value" style="font-size:17px">2025-11-22</div>
        <div class="qs__sub">Next due 2026-11-22</div>
      </div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="detail-tabs">
          <button class="active" data-pane="iso">ISO 6346</button>
          <button data-pane="csc">CSC Plate</button>
          <button data-pane="logistics">Logistics</button>
        </div>

        <div class="detail-pane active" data-pane="iso">
          <div class="card"><div class="card__head"><div class="card__title"><h3>ISO 6346 Identification</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Container Number</span><span class="info-row__val"><code>${c.owner}${c.serial}${c.checkDigit}</code></span></div>
              <div class="info-row"><span class="info-row__key">Owner Code</span><span class="info-row__val">${c.owner}</span></div>
              <div class="info-row"><span class="info-row__key">Category Identifier</span><span class="info-row__val">U (Freight)</span></div>
              <div class="info-row"><span class="info-row__key">Serial Number</span><span class="info-row__val">${c.serial}</span></div>
              <div class="info-row"><span class="info-row__key">Check Digit</span><span class="info-row__val">${c.checkDigit}</span></div>
              <div class="info-row"><span class="info-row__key">ISO Size & Type</span><span class="info-row__val"><code>${c.iso}</code></span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="csc">
          <div class="card"><div class="card__head"><div class="card__title"><h3>CSC Plate Details</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Manufacturer</span><span class="info-row__val">CIMC Group</span></div>
              <div class="info-row"><span class="info-row__key">Manufacturer Serial</span><span class="info-row__val"><code>MFG-${c.serial}-CIMC</code></span></div>
              <div class="info-row"><span class="info-row__key">Manufacture Date</span><span class="info-row__val">2022-05-18</span></div>
              <div class="info-row"><span class="info-row__key">Max Gross Weight</span><span class="info-row__val">30,480 kg</span></div>
              <div class="info-row"><span class="info-row__key">Tare Weight</span><span class="info-row__val">2,350 kg</span></div>
              <div class="info-row"><span class="info-row__key">Allowable Stacking</span><span class="info-row__val">192,000 kg</span></div>
              <div class="info-row"><span class="info-row__key">Racking Test (NED)</span><span class="info-row__val">75 kN</span></div>
              <div class="info-row"><span class="info-row__key">Next CSC Inspection</span><span class="info-row__val">2027-05-18</span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="logistics">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Logistics & Customs</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">EORI Number</span><span class="info-row__val"><code>IT12345678901</code></span></div>
              <div class="info-row"><span class="info-row__key">Seal Number</span><span class="info-row__val"><code>SEAL-${10000 + Math.abs(c.id.charCodeAt(4)*37) % 99999}</code></span></div>
              <div class="info-row"><span class="info-row__key">Current Location</span><span class="info-row__val">${c.location}</span></div>
              <div class="info-row"><span class="info-row__key">Current Trip</span><span class="info-row__val"><a href="trip-detail.html?id=TR-20481" style="color:var(--orange-600);font-weight:600">TR-20481</a></span></div>
              <div class="info-row"><span class="info-row__key">Customs Status</span><span class="info-row__val"><span class="badge badge--success">Cleared</span></span></div>
              <div class="info-row"><span class="info-row__key">Last Sealed By</span><span class="info-row__val">Luca Romano</span></div>
            </div></div>
          </div>
          <div class="card" style="margin-top:14px"><div class="card__head"><div class="card__title"><h3>Documents</h3></div></div>
            <div class="card__body"><div class="doc-chips">
              <span class="doc-chip">${icon("doc",12)} BoL-${c.id}.pdf</span>
              <span class="doc-chip">${icon("doc",12)} CSC-plate.jpg</span>
              <span class="doc-chip">${icon("doc",12)} Seal-photo.jpg</span>
              <span class="doc-chip">${icon("doc",12)} Customs-declaration.pdf</span>
            </div></div>
          </div>
        </div>
      </div>

      <aside class="detail-side">
        <div class="card side-qr">
          <div class="card__title" style="justify-content:center;margin-bottom:10px"><h3>QR Code</h3></div>
          <div class="side-qr__frame"><div id="sideQr"></div></div>
          <div class="side-qr__code">flecso://container/${c.id}</div>
          <div class="side-qr__actions">
            <button class="btn btn--sm btn--ghost" onclick="showQR('Container ${c.id}','ISO ${c.iso}')">${icon("eye",14)} View</button>
            <button class="btn btn--sm btn--ghost" id="sideQrDl">${icon("upload",14)} Save</button>
          </div>
        </div>

        <div class="card"><div class="card__head"><div class="card__title"><h3>Current Seal</h3></div></div>
          <div class="card__body">
            <div class="info-row" style="padding-top:0"><span class="info-row__key">Seal status</span><span class="info-row__val"><span class="badge badge--success">Intact</span></span></div>
            <div class="info-row"><span class="info-row__key">Applied</span><span class="info-row__val">2 days ago</span></div>
            <div class="info-row"><span class="info-row__key">By</span><span class="info-row__val">Luca Romano</span></div>
          </div>
        </div>

        <div class="card"><div class="card__head"><div class="card__title"><h3>Actions</h3></div></div>
          <div class="card__body side-actions">
            <button class="side-action">${icon("route",14)} Assign to a trip</button>
            <button class="side-action">${icon("spark",14)} Schedule inspection</button>
            <button class="side-action">${icon("doc",14)} Print BoL</button>
            <button class="side-action">${icon("upload",14)} Upload documents</button>
            <button class="side-action danger">${icon("trash",14)} Archive container</button>
          </div>
        </div>
      </aside>
    </div>
  `;

  document.querySelectorAll(".detail-tabs button").forEach(b =>
    b.addEventListener("click", () => {
      document.querySelectorAll(".detail-tabs button").forEach(x => x.classList.remove("active"));
      b.classList.add("active");
      document.querySelectorAll(".detail-pane").forEach(p => p.classList.toggle("active", p.dataset.pane === b.dataset.pane));
    })
  );

  if (window.QRCode) {
    new QRCode(document.getElementById("sideQr"), {
      text: `flecso://container/${c.id}`,
      width: 160, height: 160, colorDark: "#0A0A0B", colorLight: "#FFFFFF",
      correctLevel: QRCode.CorrectLevel.H,
    });
  }
  document.getElementById("sideQrDl")?.addEventListener("click", () => {
    const canvas = document.querySelector("#sideQr canvas"); if (!canvas) return;
    const a = document.createElement("a"); a.download = `${c.id}-qr.png`; a.href = canvas.toDataURL("image/png"); a.click();
  });
})();
