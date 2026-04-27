/* Drivers page: render table rows + Add New Driver drawer */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge, openDrawer, field, selectField, uploadBox } = window.Flecso;

  const tbody = document.getElementById("driversBody");
  if (tbody) {
    tbody.innerHTML = D.drivers.map(d => `
      <tr>
        <td><div class="cell-asset">
          <img class="asset-thumb" style="border-radius:50%" src="${d.avatar}" alt="">
          <div>
            <div class="asset-name">${d.name}</div>
            <div class="asset-sub">${d.id}</div>
          </div>
        </div></td>
        <td>${d.phone}<div class="asset-sub">${d.email}</div></td>
        <td>${d.license}</td>
        <td>${d.expiry}</td>
        <td><strong>${d.trips}</strong></td>
        <td><span style="display:inline-flex;align-items:center;gap:4px;font-weight:600">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="#FF8A2B" stroke="none"><path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/></svg>
          ${d.rating}
        </span></td>
        <td>${statusBadge(d.status)}</td>
        <td><div class="row-actions">
          <button class="mini-btn" title="View">${icon("eye",14)}</button>
          <button class="mini-btn" title="Edit">${icon("edit",14)}</button>
          <button class="mini-btn mini-btn--danger" title="Delete">${icon("trash",14)}</button>
        </div></td>
      </tr>`).join("");
  }

  function driverForm() {
    return `
      <div class="form-section">
        <h4><span class="sec-num">1</span> Personal Information</h4>
        <div class="form-grid">
          <div class="field full"><label>Profile Image</label>${uploadBox("Upload profile photo","Square image, min 400×400")}</div>
          ${field("Full Name","Mario Rossi", "text", true)}
          ${field("Email","mario.rossi@flecso.io","email")}
          ${field("Password","••••••••","password")}
          ${field("Phone","+39 000 000 0000","tel")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">2</span> License Information</h4>
        <div class="form-grid">
          ${field("License Number","IT-C-0000000")}
          ${field("License Expiry","","date")}
          ${selectField("License Status","Valid,Expiring,Expired,Suspended")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">3</span> Identity (Italy)</h4>
        <p class="muted">Italian-specific identification requirements.</p>
        <div class="form-grid">
          ${field("Codice Fiscale","RSSMRA80A01H501U")}
          ${field("Date of Birth","","date")}
          ${field("Place of Birth","Milano")}
          ${selectField("Nationality","Italian,EU Citizen,Non-EU")}
          ${field("Work Permit Number","WP-0000")}
          ${field("Medical Certificate No.","MED-0000")}
          <div class="field full">
            <label class="checkbox"><input type="checkbox"> I confirm that a criminal record check has been performed and cleared.</label>
          </div>
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">4</span> Professional Details</h4>
        <div class="form-grid">
          ${selectField("License Category","B,C,CE,D,DE")}
          ${field("CQC Number","CQC-0000")}
          ${field("CQC Expiry","","date")}
          ${field("Tachograph Card","TCH-0000")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">5</span> Documents</h4>
        <div class="form-grid">
          <div class="field">${uploadBox("License – Front","PDF, PNG, JPG")}</div>
          <div class="field">${uploadBox("License – Back","PDF, PNG, JPG")}</div>
          <div class="field">${uploadBox("Work Permit","PDF")}</div>
          <div class="field">${uploadBox("Medical Certificate","PDF")}</div>
        </div>
      </div>`;
  }
  document.getElementById("addDriverBtn")?.addEventListener("click", () =>
    openDrawer("Add New Driver", "Create a new driver profile", driverForm())
  );

  window.Flecso.editDriver = function(id) {
    const d = D.drivers.find(x => x.id === id);
    if (!d) { window.Flecso.toast("Driver not found"); return; }
    openDrawer(`Edit Driver · ${d.name}`, `Update profile for ${d.id}`, driverForm());
    const submit = document.getElementById("drawerSubmit");
    if (submit) submit.textContent = "Save Changes";
    setTimeout(() => {
      const { setField } = window.Flecso;
      setField("Full Name", d.name);
      setField("Email", d.email);
      setField("Phone", d.phone);
      setField("License Number", d.license);
      setField("License Expiry", d.expiry);
      setField("License Status", d.status === "expiring" ? "Expiring" : "Valid");
      setField("Nationality", "Italian");
      setField("License Category", "CE");
    }, 60);
  };

  const editId = new URLSearchParams(location.search).get("edit");
  if (editId) setTimeout(() => window.Flecso.editDriver(editId), 100);
})();
