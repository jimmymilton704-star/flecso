/* Containers page: render table rows + Add New Container drawer */
(function () {
  const D = window.FlecsoData;
  const { icon, statusBadge, openDrawer, field, selectField, uploadBox } = window.Flecso;

  const tbody = document.getElementById("containersBody");
  if (tbody) {
    tbody.innerHTML = D.containers.map(c => `
      <tr>
        <td><div class="cell-asset">
          <img class="asset-thumb" src="${c.image}" alt="">
          <div>
            <div class="asset-name">${c.id}</div>
            <div class="asset-sub">SN ${c.serial}-${c.checkDigit}</div>
          </div>
        </div></td>
        <td>${c.type}</td>
        <td><code style="font-family:SFMono-Regular,Menlo,monospace;font-size:12px;background:var(--ink-50);padding:2px 8px;border-radius:6px">${c.iso}</code></td>
        <td>${c.owner}</td>
        <td><strong>${c.weight}</strong> <span class="muted">tons</span></td>
        <td>${c.location}</td>
        <td>${statusBadge(c.status)}</td>
        <td><div class="row-actions">
          <button class="mini-btn mini-btn--qr" title="QR Code" onclick="showQR('Container ${c.id}','ISO ${c.iso}')">${icon("qr",14)}</button>
          <button class="mini-btn" title="View">${icon("eye",14)}</button>
          <button class="mini-btn" title="Edit">${icon("edit",14)}</button>
          <button class="mini-btn mini-btn--danger" title="Delete">${icon("trash",14)}</button>
        </div></td>
      </tr>`).join("");
  }

  function containerForm() {
    return `
      <div class="form-section">
        <h4><span class="sec-num">1</span> Basic Information</h4>
        <div class="form-grid">
          ${field("Container Number","CNT-0000", "text", true)}
          ${field("License Number","IT-0000")}
          ${selectField("Type","Dry 20ft,Dry 40ft,Reefer 20ft,Reefer 40ft,Tank,Flat Rack,Open Top")}
          ${selectField("Status","Available,In Transit,Loading,Maintenance")}
          ${field("Weight Capacity (tons)","28","number")}
          <div class="field"><label>Image</label>${uploadBox("Container image","PNG, JPG")}</div>
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">2</span> ISO 6346 Identification</h4>
        <p class="muted">International container identification code system.</p>
        <div class="form-grid">
          ${field("Owner Code","FLCU")}
          ${field("Category Identifier","U")}
          ${field("Serial Number","000000")}
          ${field("Check Digit","0")}
          ${field("ISO Code","22G1")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">3</span> CSC Plate Details</h4>
        <p class="muted">Convention for Safe Containers certification.</p>
        <div class="form-grid">
          ${field("Manufacturer Serial","MFG-0000")}
          ${field("Manufacture Date","","date")}
          ${field("Max Gross Weight (kg)","30480","number")}
          ${field("Stacking Weight (kg)","192000","number")}
          ${field("NED – Net End Door (kN)","75","number")}
        </div>
      </div>
      <div class="form-section">
        <h4><span class="sec-num">4</span> Logistics</h4>
        <div class="form-grid">
          ${field("EORI Number","IT12345678901")}
          ${field("Seal Number","SEAL-00000")}
        </div>
      </div>`;
  }
  document.getElementById("addContainerBtn")?.addEventListener("click", () =>
    openDrawer("Add New Container", "Register a container with ISO 6346 identification", containerForm())
  );

  window.Flecso.editContainer = function(id) {
    const c = D.containers.find(x => x.id === id);
    if (!c) { window.Flecso.toast("Container not found"); return; }
    openDrawer(`Edit Container · ${c.id}`, `Update details for ISO ${c.iso}`, containerForm());
    const submit = document.getElementById("drawerSubmit");
    if (submit) submit.textContent = "Save Changes";
    setTimeout(() => {
      const { setField } = window.Flecso;
      setField("Container Number", c.id);
      setField("Type", c.type);
      setField("Status", c.status.replace(/(^\w|\s\w)/g, m => m.toUpperCase()));
      setField("Weight Capacity (tons)", 30.48);
      setField("Owner Code", c.owner);
      setField("Serial Number", c.serial);
      setField("Check Digit", c.checkDigit);
      setField("ISO Code", c.iso);
    }, 60);
  };

  const editId = new URLSearchParams(location.search).get("edit");
  if (editId) setTimeout(() => window.Flecso.editContainer(editId), 100);
})();
