/* Trips page: render table rows + New Trip drawer */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge, openDrawer, field, selectField } = window.Flecso;

  const tbody = document.getElementById("tripsBody");
  if (tbody) {
    tbody.innerHTML = D.trips.map(t => `
      <tr>
        <td>
          <div class="asset-name">${t.id}</div>
          <div class="asset-sub">${t.type} · ${t.date}</div>
        </td>
        <td>${t.driver}</td>
        <td><strong>${t.truck}</strong><div class="asset-sub">${t.container}</div></td>
        <td>
          <div style="display:flex;align-items:center;gap:8px;font-weight:500">
            <span>${t.from}</span>
            <svg width="18" height="10" viewBox="0 0 24 10" fill="none"><path d="M0 5h22M18 1l4 4-4 4" stroke="#FF6B1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>${t.to}</span>
          </div>
        </td>
        <td>${t.distance} km</td>
        <td>${t.eta}</td>
        <td>${statusBadge(t.status)}</td>
        <td><div class="row-actions">
          <button class="mini-btn" title="View">${icon("eye",14)}</button>
          <button class="mini-btn" title="Edit">${icon("edit",14)}</button>
          <button class="mini-btn" title="More">${icon("dots",14)}</button>
        </div></td>
      </tr>`).join("");
  }

  function tripForm() {
    return `
      <div class="form-section">
        <h4><span class="sec-num">1</span> Trip Information</h4>
        <div class="form-grid">
          ${field("Trip ID","TR-20500", "text", true)}
          <div class="field"><label>Delivery Type <span class="req">*</span></label>
            <div class="delivery-options">
              <div class="delivery-opt active" data-opt="pd"><h5>Pickup & Delivery</h5><p>Door to door</p></div>
              <div class="delivery-opt" data-opt="std"><h5>Standard</h5><p>Regular schedule</p></div>
              <div class="delivery-opt" data-opt="exp"><h5>Express</h5><p>Priority routing</p></div>
            </div>
          </div>
          ${selectField("Driver","Luca Romano,Giovanni Esposito,Sofia Rinaldi,Alessandro Conti,Elena Marchetti")}
          ${selectField("Truck","MXL-221,MXL-108,MXL-145,MXL-330,MXL-417")}
          ${selectField("Container","CNT-4492,CNT-4501,CNT-4520,CNT-4538,CNT-4551")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">2</span> Location Details</h4>
        <p class="muted">Set pickup and drop-off with live map preview.</p>
        <div class="form-grid">
          ${field("Pickup Address","Via Roma 12, Milano, IT")}
          ${field("Drop-off Address","Piazza Castello, Torino, IT")}
        </div>
        <div class="trip-map" id="tripMiniMap" style="margin-top:12px"></div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">3</span> Calculations & Schedule</h4>
        <div class="form-grid">
          ${field("Distance (km)","142","number")}
          ${field("ETA","2h 10m")}
          ${field("Pickup Date & Time","","datetime-local")}
          ${field("Drop-off Date & Time","","datetime-local")}
          ${selectField("Status","Pending,Active,Completed,Cancelled,SOS Alert")}
          ${selectField("Payment Account","Invoice Account – FC-001,Prepaid – FC-002,Corporate – FC-003")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">4</span> Delivery Contact</h4>
        <div class="form-grid">
          ${field("Contact Name","Giulia Ferrari")}
          ${field("Contact Email","g.ferrari@acme.it","email")}
          ${field("Contact Phone","+39 000 000 0000","tel")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">5</span> Package Details</h4>
        <div class="form-grid">
          <div class="field full"><label>Description</label><textarea placeholder="Describe the contents of the shipment…"></textarea></div>
          ${field("Weight (KG)","12500","number")}
          ${field("Height (FT)","8","number")}
          ${field("Length (FT)","40","number")}
          ${field("Width (FT)","8","number")}
        </div>
      </div>`;
  }
  document.getElementById("addTripBtn")?.addEventListener("click", () =>
    openDrawer("Schedule New Trip", "Create a new trip and dispatch the fleet", tripForm())
  );

  window.Flecso.editTrip = function(id) {
    const t = D.trips.find(x => x.id === id);
    if (!t) { window.Flecso.toast("Trip not found"); return; }
    openDrawer(`Edit Trip · ${t.id}`, `${t.from} → ${t.to}`, tripForm());
    const submit = document.getElementById("drawerSubmit");
    if (submit) submit.textContent = "Save Changes";
    setTimeout(() => {
      const { setField } = window.Flecso;
      setField("Trip ID", t.id);
      setField("Driver", t.driver);
      setField("Truck", t.truck);
      setField("Container", t.container);
      setField("Pickup Address", t.from);
      setField("Drop-off Address", t.to);
      setField("Distance (km)", t.distance);
      setField("ETA", t.eta);
      setField("Status", t.status.charAt(0).toUpperCase() + t.status.slice(1));
      // Activate the right delivery-opt pill
      document.querySelectorAll(".delivery-opt").forEach(o => o.classList.remove("active"));
      const want = t.type === "Express" ? "exp" : t.type === "Standard" ? "std" : "pd";
      document.querySelector(`.delivery-opt[data-opt="${want}"]`)?.classList.add("active");
    }, 60);
  };

  const editId = new URLSearchParams(location.search).get("edit");
  if (editId) setTimeout(() => window.Flecso.editTrip(editId), 100);
})();
