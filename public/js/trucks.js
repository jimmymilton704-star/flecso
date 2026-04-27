/* Trucks page: render table rows + Add New Truck drawer */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge, openDrawer, field, selectField, uploadBox } = window.Flecso;

  const tbody = document.getElementById("trucksBody");
  if (tbody) {
    tbody.innerHTML = D.trucks.map(t => `
      <tr>
        <td><div class="cell-asset">
          <img class="asset-thumb" src="${t.image}" alt="">
          <div>
            <div class="asset-name">${t.id}</div>
            <div class="asset-sub">${t.plate}</div>
          </div>
        </div></td>
        <td>${t.category}<div class="asset-sub">${t.fuel}</div></td>
        <td><strong>${t.capacity}</strong> <span class="muted">tons</span></td>
        <td>${t.driver}</td>
        <td>${t.mileage}</td>
        <td>${t.lastService}</td>
        <td>${statusBadge(t.status)}</td>
        <td><div class="row-actions">
          <button class="mini-btn mini-btn--qr" title="QR Code" onclick="showQR('Truck ${t.id}', '${t.plate}')">${icon("qr",14)}</button>
          <button class="mini-btn" title="View">${icon("eye",14)}</button>
          <button class="mini-btn" title="Edit">${icon("edit",14)}</button>
          <button class="mini-btn mini-btn--danger" title="Delete">${icon("trash",14)}</button>
        </div></td>
      </tr>`).join("");
  }

  function truckForm() {
    return `
      <div class="form-section">
        <h4><span class="sec-num">1</span> Basic Information</h4>
        <p class="muted">Core identification and assignment details.</p>
        <div class="form-grid">
          ${field("Truck Number","MXL-000", "text", true)}
          ${field("License Plate","FA-000-XX")}
          ${field("Capacity (Tons)","24","number")}
          ${selectField("Category","Semi-Truck,Box Truck,Refrigerated,Flatbed,Tanker")}
          ${selectField("Type","Heavy,Medium,Light")}
          ${selectField("Status","Active,Maintenance,Idle,Inactive")}
          <div class="field full"><label>Truck Images</label>${uploadBox("Drop truck images here","PNG, JPG up to 5MB")}</div>
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">2</span> Identity & Legal</h4>
        <p class="muted">VIN, registration and regulatory paperwork.</p>
        <div class="form-grid">
          ${field("VIN / Chassis No.","WDB9634031L123456")}
          ${field("Registration Date","","date")}
          ${selectField("Usage Type","Owned,Leased,Rented")}
          <div class="field full"><label>Legal Documents</label>${uploadBox("Upload registration, ownership papers","PDF up to 10MB")}</div>
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">3</span> Technical Specifications</h4>
        <div class="form-grid">
          ${selectField("Vehicle Category","N1,N2,N3")}
          ${field("Gross Vehicle Weight (kg)","26000","number")}
          ${field("Payload Capacity (kg)","18000","number")}
          ${field("Axles","3","number")}
          ${selectField("Euro Class","Euro VI,Euro V,Euro IV")}
          ${selectField("Fuel Type","Diesel,Electric,Hybrid,LNG,Hydrogen")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">4</span> Compliance</h4>
        <div class="form-grid">
          ${field("Last Inspection Date","","date")}
          ${field("Insurance Provider","Generali")}
          ${field("Insurance Expiry","","date")}
          ${field("Tachograph Expiry","","date")}
          ${field("Road Tax Expiry","","date")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">5</span> Maintenance</h4>
        <div class="form-grid">
          ${field("Current Mileage (km)","248120","number")}
          ${field("Last Service Mileage (km)","240000","number")}
        </div>
      </div>`;
  }
  document.getElementById("addTruckBtn")?.addEventListener("click", () =>
    openDrawer("Add New Truck", "Register a new vehicle into the Flecso fleet", truckForm())
  );

  /* Edit flow — reused by the row mini-btn and detail-page Edit */
  window.Flecso.editTruck = function(id) {
    const t = D.trucks.find(x => x.id === id);
    if (!t) { window.Flecso.toast("Truck not found"); return; }
    openDrawer(`Edit Truck · ${t.id}`, `Update details for plate ${t.plate}`, truckForm());
    // Swap submit label for clarity
    const submit = document.getElementById("drawerSubmit");
    if (submit) submit.textContent = "Save Changes";
    setTimeout(() => {
      const { setField } = window.Flecso;
      setField("Truck Number", t.id);
      setField("License Plate", t.plate);
      setField("Capacity (Tons)", t.capacity);
      setField("Category", t.category);
      setField("Status", t.status.charAt(0).toUpperCase() + t.status.slice(1));
      setField("Fuel Type", t.fuel);
      setField("Current Mileage (km)", String(t.mileage).replace(/\D/g,""));
    }, 60);
  };

  /* Honor ?edit=ID when landing on the list page */
  const editId = new URLSearchParams(location.search).get("edit");
  if (editId) setTimeout(() => window.Flecso.editTruck(editId), 100);
})();
