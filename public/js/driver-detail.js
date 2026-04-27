/* Driver Detail page */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge } = window.Flecso;
  const id = new URLSearchParams(location.search).get("id") || (D.drivers[0] && D.drivers[0].id);
  const d = D.drivers.find(x => x.id === id) || D.drivers[0];
  const root = document.getElementById("detailRoot");
  if (!d) { root.innerHTML = `<p class="muted">Driver not found. <a href="drivers.html">Back to list</a></p>`; return; }
  document.title = `${d.name} · Driver — Flecso`;

  const onTime = (92 + (d.rating - 4.5) * 10).toFixed(1);
  const assignedTruck = D.trucks.find(t => t.driver === d.name);

  root.innerHTML = `
    <a href="drivers.html" class="detail-back">${icon("trip",14).replace('width="18" height="18"','width="14" height="14"')} Back to Drivers</a>

    <div class="detail-hero">
      <img class="detail-hero__img rounded-full" src="${d.avatar}" alt="" />
      <div class="detail-hero__body">
        <div class="detail-hero__meta">
          <span class="detail-hero__id">${d.id}</span>
          ${statusBadge(d.status)}
          <span class="badge badge--orange">★ ${d.rating}</span>
        </div>
        <h1>${d.name}</h1>
        <div class="detail-hero__sub">
          <span>${icon("mail",14).replace('<path d="M1', '<path d="M1')} ${d.email}</span>
          <span>${icon("phone",14)} ${d.phone}</span>
          <span>${icon("doc",14)} License ${d.license}</span>
        </div>
      </div>
      <div class="detail-hero__actions">
        <button class="btn btn--ghost">${icon("mail",16).replace('<path d="M1','<path d="M1')} Message</button>
        <button class="btn btn--ghost">${icon("edit",16)} Edit</button>
        <button class="btn btn--primary">${icon("route",16)} Assign Trip</button>
      </div>
    </div>

    <div class="detail-quickstats">
      <div class="qs"><div class="qs__label">${icon("route",12)} Trips Completed</div><div class="qs__value">${d.trips}</div><div class="qs__sub">Lifetime</div></div>
      <div class="qs"><div class="qs__label">${icon("check",12)} On-time Rate</div><div class="qs__value">${onTime}%</div><div class="qs__sub">Last 90 days</div></div>
      <div class="qs"><div class="qs__label">${icon("cal",12)} Hours This Month</div><div class="qs__value">148h</div><div class="qs__sub">of 176h max</div></div>
      <div class="qs"><div class="qs__label">${icon("doc",12)} License Expiry</div><div class="qs__value" style="font-size:17px">${d.expiry}</div><div class="qs__sub">${d.status === "expiring" ? "⚠ Expires soon" : "Valid"}</div></div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="detail-tabs">
          <button class="active" data-pane="personal">Personal</button>
          <button data-pane="license">License</button>
          <button data-pane="identity">Identity (Italy)</button>
          <button data-pane="professional">Professional</button>
          <button data-pane="documents">Documents</button>
        </div>

        <div class="detail-pane active" data-pane="personal">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Personal Information</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Full Name</span><span class="info-row__val">${d.name}</span></div>
              <div class="info-row"><span class="info-row__key">Driver ID</span><span class="info-row__val"><code>${d.id}</code></span></div>
              <div class="info-row"><span class="info-row__key">Email</span><span class="info-row__val">${d.email}</span></div>
              <div class="info-row"><span class="info-row__key">Phone</span><span class="info-row__val">${d.phone}</span></div>
              <div class="info-row"><span class="info-row__key">Status</span><span class="info-row__val">${statusBadge(d.status)}</span></div>
              <div class="info-row"><span class="info-row__key">Date Joined</span><span class="info-row__val">2024-08-12</span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="license">
          <div class="card"><div class="card__head"><div class="card__title"><h3>License Information</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">License Number</span><span class="info-row__val"><code>${d.license}</code></span></div>
              <div class="info-row"><span class="info-row__key">License Category</span><span class="info-row__val">CE</span></div>
              <div class="info-row"><span class="info-row__key">Issuing Authority</span><span class="info-row__val">MCTC Milano</span></div>
              <div class="info-row"><span class="info-row__key">Issue Date</span><span class="info-row__val">2023-06-14</span></div>
              <div class="info-row"><span class="info-row__key">Expiry Date</span><span class="info-row__val">${d.expiry}</span></div>
              <div class="info-row"><span class="info-row__key">Status</span><span class="info-row__val"><span class="badge ${d.status === "expiring" ? "badge--warn" : "badge--success"}"><span class="badge-dot"></span>${d.status === "expiring" ? "Expiring" : "Valid"}</span></span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="identity">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Italian Identity</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">Codice Fiscale</span><span class="info-row__val"><code>${d.name.split(" ").map(n => n.slice(0,3).toUpperCase()).join("")}80A01H501U</code></span></div>
              <div class="info-row"><span class="info-row__key">Date of Birth</span><span class="info-row__val">1988-04-12</span></div>
              <div class="info-row"><span class="info-row__key">Place of Birth</span><span class="info-row__val">Milano, IT</span></div>
              <div class="info-row"><span class="info-row__key">Nationality</span><span class="info-row__val">Italian</span></div>
              <div class="info-row"><span class="info-row__key">Work Permit</span><span class="info-row__val">N/A (EU citizen)</span></div>
              <div class="info-row"><span class="info-row__key">Medical Certificate</span><span class="info-row__val">MED-${Math.abs(d.id.charCodeAt(4)*71).toString().slice(0,4)} · valid until 2027-03-20</span></div>
              <div class="info-row"><span class="info-row__key">Criminal Record Check</span><span class="info-row__val"><span class="badge badge--success">Cleared</span></span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="professional">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Professional Details</h3></div></div>
            <div class="card__body"><div class="info-grid">
              <div class="info-row"><span class="info-row__key">License Category</span><span class="info-row__val">CE (Articulated vehicles)</span></div>
              <div class="info-row"><span class="info-row__key">CQC Number</span><span class="info-row__val"><code>CQC-${Math.abs(d.id.charCodeAt(4)*13).toString().slice(0,5)}</code></span></div>
              <div class="info-row"><span class="info-row__key">CQC Expiry</span><span class="info-row__val">2028-02-14</span></div>
              <div class="info-row"><span class="info-row__key">Tachograph Card</span><span class="info-row__val"><code>TCH-${Math.abs(d.id.charCodeAt(5)*17).toString().slice(0,5)}</code></span></div>
              <div class="info-row"><span class="info-row__key">ADR Certification</span><span class="info-row__val">Class 3 & 8 · valid</span></div>
              <div class="info-row"><span class="info-row__key">Years Driving</span><span class="info-row__val">12 years</span></div>
            </div></div>
          </div>
        </div>

        <div class="detail-pane" data-pane="documents">
          <div class="card"><div class="card__head"><div class="card__title"><h3>Documents</h3></div><button class="btn btn--sm btn--primary">${icon("plus",14)} Upload</button></div>
            <div class="card__body">
              <div class="doc-chips" style="margin-bottom:12px">
                <span class="doc-chip">${icon("doc",12)} License-front.jpg</span>
                <span class="doc-chip">${icon("doc",12)} License-back.jpg</span>
                <span class="doc-chip">${icon("doc",12)} CQC-card.pdf</span>
                <span class="doc-chip">${icon("doc",12)} Tachograph-card.pdf</span>
                <span class="doc-chip">${icon("doc",12)} Medical-cert-2026.pdf</span>
                <span class="doc-chip">${icon("doc",12)} ADR-certificate.pdf</span>
                <span class="doc-chip">${icon("doc",12)} Contract-signed.pdf</span>
              </div>
              <p class="muted">All documents are encrypted at rest and accessible only to HR and compliance officers.</p>
            </div>
          </div>
        </div>
      </div>

      <aside class="detail-side">
        ${assignedTruck ? `
          <div class="card"><div class="card__head"><div class="card__title"><h3>Assigned Truck</h3></div></div>
            <div class="card__body">
              <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                <img src="${assignedTruck.image}" style="width:52px;height:52px;border-radius:10px;object-fit:cover" alt="">
                <div style="flex:1">
                  <div class="assignee__name">${assignedTruck.id}</div>
                  <div class="assignee__sub">${assignedTruck.plate} · ${assignedTruck.category}</div>
                </div>
              </div>
              <a href="truck-detail.html?id=${assignedTruck.id}" class="btn btn--sm btn--ghost btn--block">View truck</a>
            </div>
          </div>` : `
          <div class="card"><div class="card__body" style="text-align:center;padding:22px">
            <div style="width:48px;height:48px;margin:0 auto 10px;border-radius:12px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-400)">${icon("truck",20)}</div>
            <h4>No truck assigned</h4>
            <p class="muted">Assign a vehicle when dispatching.</p>
          </div></div>`}

        <div class="card"><div class="card__head"><div class="card__title"><h3>Recent Activity</h3></div></div>
          <div class="card__body">
            <div class="info-row" style="padding-top:0"><span class="info-row__key">Completed TR-20476</span><span class="info-row__val">3h ago</span></div>
            <div class="info-row"><span class="info-row__key">Started shift</span><span class="info-row__val">Today 06:00</span></div>
            <div class="info-row"><span class="info-row__key">Safety training</span><span class="info-row__val">2 days ago</span></div>
          </div>
        </div>

        <div class="card"><div class="card__head"><div class="card__title"><h3>Actions</h3></div></div>
          <div class="card__body side-actions">
            <button class="side-action">${icon("route",14)} Assign to a trip</button>
            <button class="side-action">${icon("mail",14).replace('<path d="M1','<path d="M1')} Send a message</button>
            <button class="side-action">${icon("doc",14)} Generate timesheet</button>
            <button class="side-action">${icon("upload",14)} Upload document</button>
            <button class="side-action danger">${icon("trash",14)} Deactivate driver</button>
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
})();
