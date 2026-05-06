/* =========================================================
   Flecso — Shared shell
   Sidebar, topbar popovers (notifications, messages, profile),
   drawer, QR modal, toast, form helpers, global button wiring.
   ========================================================= */
(function () {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  /* ---------- Icons ---------- */
  const ICONS = {
    truck: `<path d="M3 7h10v10H3z"/><path d="M13 10h5l3 3v4h-8"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>`,
    box:   `<rect x="3" y="7" width="18" height="11" rx="1.5"/><path d="M7 7v11M12 7v11M17 7v11"/>`,
    user:  `<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/>`,
    trip:  `<path d="m8 13 4-4 4 4-4 4z"/><path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>`,
    qr:    `<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="17" width="3" height="3"/><rect x="18" y="14" width="3" height="3"/><path d="M14 14h3v3M17 20h4M14 17v4"/>`,
    plus:  `<path d="M12 5v14M5 12h14"/>`,
    eye:   `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/>`,
    edit:  `<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"/>`,
    trash: `<path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>`,
    dots:  `<circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>`,
    check: `<path d="M20 6 9 17l-5-5"/>`,
    upload:`<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>`,
    filter:`<path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>`,
    spark: `<path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/>`,
    export:`<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>`,
    cal:   `<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>`,
    map:   `<path d="M9 3 3 6v15l6-3 6 3 6-3V3l-6 3z"/><path d="M9 3v15M15 6v15"/>`,
    bell:  `<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/>`,
    doc:   `<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>`,
    phone: `<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z"/>`,
    route: `<circle cx="6" cy="19" r="3"/><circle cx="18" cy="5" r="3"/><path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"/>`,
  };
// Set active based on current URL
document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll("#sidebarNav .nav-link");

    links.forEach(link => {
      if (link.href === window.location.href) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  });
  const icon = (name, size = 18) =>
    `<svg viewBox="0 0 24 24" width="${size}" height="${size}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${ICONS[name] || ""}</svg>`;

  /* ---------- Status badges ---------- */
  const statusBadge = (status) => {
    const key = String(status).toLowerCase();
    const map = {
      "active":["badge--success","Active"], "in-transit":["badge--success","In Transit"], "completed":["badge--success","Completed"],
      "available":["badge--info","Available"], "loading":["badge--info","Loading"],
      "idle":["badge--neutral","Idle"], "off-duty":["badge--neutral","Off Duty"], "inactive":["badge--neutral","Inactive"],
      "maintenance":["badge--warn","Maintenance"], "delayed":["badge--warn","Delayed"], "on-leave":["badge--warn","On Leave"], "expiring":["badge--warn","Expiring"],
      "cancelled":["badge--danger","Cancelled"], "pending":["badge--neutral","Pending"],
    };
    const [cls, label] = map[key] || ["badge--neutral", status];
    return `<span class="badge ${cls}"><span class="badge-dot"></span>${label}</span>`;
  };

  /* ---------- Form helpers ---------- */
  const field = (label, placeholder, type = "text", required = false) =>
    `<div class="field"><label>${label}${required?`<span class="req">*</span>`:""}</label><input class="input" type="${type}" placeholder="${placeholder}" /></div>`;
  const selectField = (label, options) =>
    `<div class="field"><label>${label}</label><select>${options.split(",").map(o => `<option>${o.trim()}</option>`).join("")}</select></div>`;
  const uploadBox = (title, hint) =>
    `<div class="upload">${icon("upload",20)}<strong>${title}</strong><span>${hint}</span></div>`;

  /* ---------- Toast ---------- */
  let toastTO;
  function toast(msg) {
    const el = $("#toast");
    if (!el) return;
    el.textContent = msg;
    el.classList.add("show");
    clearTimeout(toastTO);
    toastTO = setTimeout(() => el.classList.remove("show"), 2600);
  }

  /* ---------- Drawer ---------- */
  function openDrawer(title, subtitle, bodyHtml) {
    $("#drawerTitle").textContent = title;
    $("#drawerSubtitle").textContent = subtitle;
    $("#drawerBody").innerHTML = bodyHtml;
    $("#drawer").classList.add("open");

    $$(".delivery-opt").forEach(o => o.addEventListener("click", () => {
      $$(".delivery-opt").forEach(x => x.classList.remove("active"));
      o.classList.add("active");
    }));

    const miniEl = $("#tripMiniMap");
    if (miniEl && window.L) {
      const m = L.map(miniEl, { zoomControl: false, attributionControl: false, scrollWheelZoom: false }).setView([45.25, 8.5], 7);
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { subdomains: 'abcd' }).addTo(m);
      L.marker([45.4642, 9.1900]).addTo(m);
      L.marker([45.0703, 7.6869]).addTo(m);
      const route = L.polyline([[45.4642,9.1900],[45.25,8.6],[45.0703,7.6869]], { color: "#FF6B1A", weight: 3, dashArray: "6,6" }).addTo(m);
      m.fitBounds(route.getBounds(), { padding: [20,20] });
    }
  }
  function closeAll() {
    $("#drawer")?.classList.remove("open");
    $("#qrModal")?.classList.remove("open");
  }
  document.addEventListener("click", e => {
    if (e.target.hasAttribute("data-close") || e.target.closest("[data-close]")) closeAll();
  });
  document.addEventListener("keydown", e => { if (e.key === "Escape") closeAll(); });
  $("#drawerSubmit")?.addEventListener("click", () => { closeAll(); toast("Record saved successfully"); });

  /* ---------- QR Modal ---------- */
  function showQR(title, data) {
    $("#qrTitle").textContent = title;
    $("#qrSubtitle").textContent = "Scan to access asset details";
    const payload = `${String(data).replace(/\s+/g,"-")}`;
    // $("#qrData").textContent = payload;
    const host = $("#qrCanvas");
    host.innerHTML = "";
    if (window.QRCode) {
      new QRCode(host, {
        text: payload, width: 240, height: 240,
        colorDark: "#0A0A0B", colorLight: "#FFFFFF",
        correctLevel: QRCode.CorrectLevel.H,
      });
    }
    $("#qrModal").classList.add("open");
  }
  function qrDataUrl() {
    const host = $("#qrCanvas");
    const canvas = host.querySelector("canvas");
    if (canvas) return canvas.toDataURL("image/png");
    const img = host.querySelector("img");
    return img ? img.src : "";
  }
  $("#qrDownload")?.addEventListener("click", () => {
    const url = qrDataUrl(); if (!url) return;
    const link = document.createElement("a");
    link.download = ($("#qrTitle").textContent || "qr") + ".png";
    link.href = url; link.click();
  });
  $("#qrPrint")?.addEventListener("click", () => {
    const dataUrl = qrDataUrl();
    const w = window.open("", "_blank");
    w.document.write(`<html><head><title>Print QR</title></head><body style="display:grid;place-items:center;height:100vh;font-family:Inter"><div style="text-align:center"><h2>${$("#qrTitle").textContent}</h2><img src="${dataUrl}" style="width:280px;height:280px"><p>${$("#qrData").textContent}</p></div><script>window.onload=()=>window.print()<\/script></body></html>`);
    w.document.close();
  });

  /* ---------- Mobile sidebar ---------- */
  function setSidebar(open) {
    $("#sidebar")?.classList.toggle("open", open);
    $("#sidebarBackdrop")?.classList.toggle("open", open);
  }
  $("#menuToggle")?.addEventListener("click", e => {
    e.stopPropagation();
    setSidebar(!$("#sidebar").classList.contains("open"));
  });
  $("#sidebarBackdrop")?.addEventListener("click", () => setSidebar(false));
  // $("#sidebarNav")?.addEventListener("click", e => {
  //   if (e.target.closest(".nav-link") && window.innerWidth <= 980) setSidebar(false);
  // });

  /* ---------- Notifications popover ---------- */
  const notifications = [
    { id:1, icon:"bell",  tone:"danger",  title:"SOS alert from Luca Romano",   sub:"A1 Milano-Napoli · KM 412 — response team dispatched", time:"2m",  unread:true },
    { id:2, icon:"spark", tone:"warn",    title:"Maintenance due for MXL-108",   sub:"Scheduled service window opens in 3 days", time:"18m", unread:true },
    { id:3, icon:"doc",   tone:"orange",  title:"Driver license expiring soon",  sub:"Francesca Rizzo's license expires 2026-05-14", time:"1h",  unread:true },
    { id:4, icon:"check", tone:"success", title:"Trip TR-20476 completed",        sub:"Verona → Vienna delivered on-time", time:"3h",  unread:false },
    { id:5, icon:"truck", tone:"info",    title:"Truck MXL-502 returned to base", sub:"Available for dispatch", time:"5h",  unread:false },
    { id:6, icon:"box",   tone:"success", title:"Container CNT-4492 loaded",     sub:"Bologna Hub · 18.4 tons", time:"7h",  unread:false },
  ];
  function renderNotifications() {
    const list = $("#notifyList"); if (!list) return;
    list.innerHTML = notifications.length === 0
      ? `<div class="notify-empty">You're all caught up 🎉</div>`
      : notifications.map(n => `
        <div class="notify-item ${n.unread ? "unread":""}" data-id="${n.id}">
          <div class="notify-item__icon notify-item__icon--${n.tone}">${icon(n.icon,16)}</div>
          <div><div class="notify-item__title">${n.title}</div><div class="notify-item__sub">${n.sub}</div></div>
          <span class="notify-item__time">${n.time}</span>
        </div>`).join("");
    const unread = notifications.filter(n => n.unread).length;
    const badge = $("#notifyBadge");
    if (badge) { badge.textContent = unread; badge.classList.toggle("hidden", unread === 0); }
    const count = $("#notifyCount");
    if (count) count.textContent = unread === 0 ? "All caught up" : `${unread} unread`;
  }
  renderNotifications();
  $("#notifyBtn")?.addEventListener("click", e => {
    e.stopPropagation();
    $("#msgPop")?.classList.remove("open");
    $("#profilePop")?.classList.remove("open");
    $("#notifyPop")?.classList.toggle("open");
  });
  document.addEventListener("click", e => {
    const pop = $("#notifyPop");
    if (!pop?.classList.contains("open")) return;
    if (!e.target.closest(".notify-wrap")) pop.classList.remove("open");
  });
  $("#notifyMarkAll")?.addEventListener("click", e => {
    e.stopPropagation();
    notifications.forEach(n => n.unread = false);
    renderNotifications();
    toast("All notifications marked as read");
  });
  document.addEventListener("click", e => {
    const item = e.target.closest("#notifyList .notify-item");
    if (!item) return;
    const id = Number(item.dataset.id);
    const n = notifications.find(x => x.id === id);
    if (n && n.unread) { n.unread = false; renderNotifications(); }
  });

  /* ---------- Messages popover ---------- */
  const messages = [
    { id:1, avatar:"https://i.pravatar.cc/80?img=12", name:"Luca Romano",       preview:"Traffic cleared — I'm back on schedule for Turin.",     time:"4m",  unread:true },
    { id:2, avatar:"https://i.pravatar.cc/80?img=45", name:"Sofia Rinaldi",     preview:"Need a swap for tomorrow's Rome run, possible?",        time:"1h",  unread:true },
    { id:3, avatar:"https://i.pravatar.cc/80?img=33", name:"Giovanni Esposito", preview:"Crossed the border — container seal intact.",           time:"3h",  unread:false },
    { id:4, avatar:"https://i.pravatar.cc/80?img=48", name:"Elena Marchetti",   preview:"Thanks for approving the reroute ✌️",                   time:"Yesterday", unread:false },
    { id:5, avatar:"https://i.pravatar.cc/80?img=22", name:"Davide Ferrari",    preview:"Geneva depot received the shipment — paperwork signed.", time:"Yesterday", unread:false },
  ];
  function renderMessages() {
    const list = $("#msgList"); if (!list) return;
    list.innerHTML = messages.map(m => `
      <div class="notify-item ${m.unread ? "unread":""}" data-msg-id="${m.id}">
        <img class="notify-item__icon" src="${m.avatar}" alt="" style="border-radius:50%;object-fit:cover">
        <div><div class="notify-item__title">${m.name}</div><div class="notify-item__sub">${m.preview}</div></div>
        <span class="notify-item__time">${m.time}</span>
      </div>`).join("");
    const unread = messages.filter(m => m.unread).length;
    const badge = $("#msgBadge");
    if (badge) { badge.textContent = unread; badge.classList.toggle("hidden", unread === 0); }
    const count = $("#msgCount");
    if (count) count.textContent = unread === 0 ? "All caught up" : `${unread} unread`;
  }
  renderMessages();
  $("#msgBtn")?.addEventListener("click", e => {
    e.stopPropagation();
    $("#notifyPop")?.classList.remove("open");
    $("#profilePop")?.classList.remove("open");
    $("#msgPop")?.classList.toggle("open");
  });
  document.addEventListener("click", e => {
    const pop = $("#msgPop");
    if (!pop?.classList.contains("open")) return;
    if (!e.target.closest("#msgPop") && !e.target.closest("#msgBtn")) pop.classList.remove("open");
  });
  $("#msgMarkAll")?.addEventListener("click", e => {
    e.preventDefault(); e.stopPropagation();
    messages.forEach(m => m.unread = false);
    renderMessages();
    toast("All messages marked as read");
  });
  $("#msgCompose")?.addEventListener("click", e => {
    e.stopPropagation();
    $("#msgPop").classList.remove("open");
    toast("New message composer — coming soon");
  });
  document.addEventListener("click", e => {
    const item = e.target.closest(".notify-item[data-msg-id]");
    if (!item) return;
    const id = Number(item.dataset.msgId);
    const m = messages.find(x => x.id === id);
    if (m && m.unread) { m.unread = false; renderMessages(); }
  });

  /* ---------- Profile dropdown ---------- */
  $("#profileBtn")?.addEventListener("click", e => {
    e.stopPropagation();
    $("#notifyPop")?.classList.remove("open");
    $("#msgPop")?.classList.remove("open");
    $("#profilePop")?.classList.toggle("open");
  });
  document.addEventListener("click", e => {
    const pop = $("#profilePop");
    if (!pop?.classList.contains("open")) return;
    if (!e.target.closest("#profilePop") && !e.target.closest("#profileBtn")) pop.classList.remove("open");
  });
  document.addEventListener("click", e => {
    const link = e.target.closest(".profile-link");
    if (!link) return;
    e.preventDefault(); e.stopPropagation();
    $("#profilePop")?.classList.remove("open");
    const action = link.dataset.action;
    const goto = (hash) => location.href = hash;
    if (action === "profile")      goto("setting#personal");
    else if (action === "settings")goto("setting");
    else if (action === "billing") goto("setting#subscription");
    else if (action === "help")    goto("setting#support");
    else if (action === "logout")  {
      toast("Signing out…");
      setTimeout(() => location.href = "login.html", 600);
    }
  });

  /* ---------- Global button wiring ---------- */
  document.addEventListener("click", e => {
    // Filter tab groups
    const filterBtn = e.target.closest(".filters button");
    if (filterBtn) {
      filterBtn.parentElement.querySelectorAll("button").forEach(b => b.classList.remove("active"));
      filterBtn.classList.add("active");
      toast(`Filter: ${filterBtn.textContent.trim()}`);
      return;
    }
    // Pagination
    const pagerBtn = e.target.closest(".pager button");
    if (pagerBtn) {
      const label = pagerBtn.textContent.trim();
      if (label === "‹" || label === "›" || label === "…") { toast("Navigating…"); return; }
      pagerBtn.parentElement.querySelectorAll("button").forEach(b => b.classList.remove("active"));
      pagerBtn.classList.add("active");
      return;
    }
    // Mini row action buttons
    const mini = e.target.closest(".mini-btn");
    if (mini) {
      if (mini.classList.contains("mini-btn--qr")) return;
      if (mini.classList.contains("mini-btn--danger")) {
        if (confirm("Delete this record? This can be undone within 30 days.")) toast("Record deleted");
        return;
      }
      const title = mini.getAttribute("title") || "";
      if (title === "View") {
        const row = mini.closest("tr");
        const bc = document.body.classList;
        // Drivers table puts the name in .asset-name and the ID in .asset-sub
        const isDrivers = bc.contains("page-drivers");
        const idCell = row?.querySelector(isDrivers ? ".asset-sub" : ".asset-name");
        const id = idCell?.textContent.trim();
        if (!id) { toast("No record ID found"); return; }
        if (bc.contains("page-trucks"))     { location.href = `truck-detail.html?id=${encodeURIComponent(id)}`;     return; }
        if (bc.contains("page-containers")) { location.href = `container-detail.html?id=${encodeURIComponent(id)}`; return; }
        if (isDrivers)                      { location.href = `driver-detail.html?id=${encodeURIComponent(id)}`;    return; }
        if (bc.contains("page-trips"))      { location.href = `trip-detail.html?id=${encodeURIComponent(id)}`;      return; }
        toast("Opening details…");
        return;
      }
      if (title === "Edit") {
        const row = mini.closest("tr");
        const bc = document.body.classList;
        const isDrivers = bc.contains("page-drivers");
        const idCell = row?.querySelector(isDrivers ? ".asset-sub" : ".asset-name");
        const id = idCell?.textContent.trim();
        if (id) {
          if (bc.contains("page-trucks")     && window.Flecso.editTruck)     { window.Flecso.editTruck(id);     return; }
          if (bc.contains("page-containers") && window.Flecso.editContainer) { window.Flecso.editContainer(id); return; }
          if (isDrivers                      && window.Flecso.editDriver)    { window.Flecso.editDriver(id);    return; }
          if (bc.contains("page-trips")      && window.Flecso.editTrip)      { window.Flecso.editTrip(id);      return; }
        }
        toast("Opening editor…");
        return;
      }
      toast("Action triggered");
      return;
    }
  });

  // Upload boxes → hidden file input
  let hiddenFileInput;
  function triggerFilePicker() {
    if (!hiddenFileInput) {
      hiddenFileInput = document.createElement("input");
      hiddenFileInput.type = "file";
      hiddenFileInput.style.display = "none";
      hiddenFileInput.addEventListener("change", () => {
        if (hiddenFileInput.files.length) toast(`Uploaded: ${hiddenFileInput.files[0].name}`);
        hiddenFileInput.value = "";
      });
      document.body.appendChild(hiddenFileInput);
    }
    hiddenFileInput.click();
  }
  document.addEventListener("click", e => {
    if (e.target.closest(".upload")) triggerFilePicker();
  });

  // Demo CTA buttons (Export, Quick add, View all, etc.)
  document.addEventListener("click", e => {
    const btn = e.target.closest("button");
    if (!btn) return;
    if (btn.closest(".notify-pop")) return;
    const reserved = ["menuToggle","notifyBtn","msgBtn","profileBtn","msgCompose","msgMarkAll","notifyMarkAll","drawerSubmit","qrDownload","qrPrint","addTruckBtn","addContainerBtn","addDriverBtn","addTripBtn"];
    if (btn.id && reserved.includes(btn.id)) return;
    // if (btn.classList.contains("nav-link") || btn.classList.contains("tab-item") || btn.classList.contains("delivery-opt") || btn.classList.contains("icon-btn") || btn.classList.contains("mini-btn")) return;
    if (btn.hasAttribute("data-close")) return;
    if (btn.closest(".filters") || btn.closest(".pager")) return;
    if (btn.closest(".modal__footer") || btn.closest(".drawer__footer")) return;

    const label = btn.textContent.trim();

    if (btn.closest(".upgrade-card")) { location.href = "settings.html#subscription"; return; }
    if (btn.classList.contains("chip")) { toast("All services operational · 99.98% uptime"); return; }
    if (btn.closest(".sos-item")) { toast("Emergency team dispatched to location"); return; }

    if (btn.closest(".plan")) {
      const plan = btn.closest(".plan").querySelector("h3")?.textContent;
      toast(label === "Current Plan" ? `${plan} is your current plan` : `Upgrade to ${plan} — redirecting to checkout…`);
      return;
    }

    if (btn.closest(".tab-panel[data-panel='support']")) {
      if (label.includes("Help Center")) toast("Opening Help Center…");
      else if (label.includes("support@")) toast(`Email copied · ${label}`);
      else if (label.startsWith("+39")) toast(`Calling ${label}…`);
      return;
    }

    if (btn.closest(".tab-panel[data-panel='legal']")) {
      const t = btn.closest("div").querySelector("h5")?.textContent || "document";
      toast(label === "View" ? `Opening ${t}` : `Editing ${t}`);
      return;
    }

    if (label === "Set as default") {
      const card = btn.closest(".card");
      $$(".tab-panel[data-panel='language'] .card").forEach(c => {
        c.style.borderColor = ""; c.style.boxShadow = "";
        c.querySelector(".badge")?.remove();
        const b = c.querySelector("button");
        if (b && b !== btn) { b.className = "btn btn--ghost btn--sm"; b.textContent = "Set as default"; b.style.marginTop = "10px"; }
      });
      card.style.borderColor = "var(--orange-400)";
      card.style.boxShadow = "0 0 0 4px rgba(255,107,26,.08)";
      const h4 = card.querySelector("h4")?.textContent || "Language";
      btn.outerHTML = `<span class="badge badge--orange" style="margin-top:10px">Active</span>`;
      toast(`Language set to ${h4}`);
      return;
    }

    if (label === "New Role" || label.includes("New Role")) { toast("Role creation wizard — coming soon"); return; }
    if (label === "Save changes") { toast("Company profile saved"); return; }
    if (label === "Update")       { toast("Profile updated successfully"); return; }
    if (label === "Remove")       { toast("Logo removed"); return; }
    if (label.includes("Upload new")) { triggerFilePicker(); return; }

    if (btn.closest(".page-head__actions") || btn.closest(".card__head")) {
      if (label.includes("Last 30") || label.match(/Last \d+ days|Today|Yesterday|This week|This month/)) {
        openDateRangeMenu(btn); return;
      }
      if (label.includes("Quick add")) { openQuickAddMenu(btn); return; }
      if (label.includes("Alert settings")) { location.href = "settings.html#notifications"; return; }
      if (label.includes("Test alert")) {
        if (confirm("Send a test SOS alert?\n\nA mock incident will be logged and a push notification will be sent to all on-call dispatchers.")) {
          toast("Test SOS alert dispatched · dispatchers notified");
        }
        return;
      }
      if (label.includes("Export"))    { exportCsv(); return; }
      if (label.includes("Calendar"))      { toast("Opening calendar view…"); return; }
      if (label.includes("Map view"))      { toast("Opening map view…"); return; }
      if (label.includes("Schedule trip")) { location.href = "trips.html"; return; }
      if (label === "View all" || label === "See all" || label === "View"){ toast("Opening full list…"); return; }
      if (label.includes("Filters"))       { toast("Advanced filters panel — coming soon"); return; }
      if (label.includes("Log Service")) { toast("Service log entry form — coming soon"); return; }
    }

    // Detail-page hero actions + side actions
    if (btn.closest(".detail-hero__actions") || btn.closest(".side-action") || btn.classList.contains("side-action")) {
      // Assign / Reassign flows → go to trips page and open the New Trip drawer
      if (/Assign (to )?Trip|Assign to a trip|Reassign trip/i.test(label)) {
        location.href = "trips.html?new=1"; return;
      }
      // Trip-specific
      if (label.includes("Notify Driver") || label.includes("Notify driver")) { toast("Driver notified · push alert sent"); return; }
      if (label.includes("Reroute"))      { toast("Reroute planner — coming soon"); return; }
      if (label.includes("Cancel trip"))  { if (confirm("Cancel this trip? Driver and customer will be notified.")) toast("Trip cancelled · stakeholders notified"); return; }
      if (label.includes("Download BoL") || label.includes("Print BoL")) { toast("Generating Bill of Lading PDF…"); return; }
      // Truck / Container
      if (label.includes("Schedule service") || label.includes("Schedule inspection")) { toast("Maintenance calendar opened"); return; }
      if (label.includes("Archive"))       { if (confirm("Archive this record? It will be hidden from active lists.")) toast("Record archived"); return; }
      if (label.includes("Deactivate"))    { if (confirm("Deactivate this driver? They won't be assignable to trips.")) toast("Driver deactivated"); return; }
      // Driver
      if (label.includes("Send a message")|| label === "Message") { toast(`Opening conversation with ${document.querySelector(".detail-hero h1")?.textContent || "driver"}…`); return; }
      if (label.includes("Generate timesheet")) { toast("Timesheet PDF generated"); return; }
      if (label.includes("Generate report"))    { toast("Report PDF generated"); return; }
      if (label.includes("Upload document") || label.includes("Upload documents") || label.includes("Attach document")) { triggerFilePicker(); return; }
      // Shared
      if (label === "Edit") {
        const bc = document.body.classList;
        const heroTitle = document.querySelector(".detail-hero h1")?.textContent || "record";
        if (bc.contains("page-trucks"))     { location.href = `trucks.html?edit=${encodeURIComponent(heroTitle)}`; return; }
        if (bc.contains("page-containers")) { location.href = `containers.html?edit=${encodeURIComponent(heroTitle)}`; return; }
        if (bc.contains("page-drivers"))    { location.href = `drivers.html?edit=${encodeURIComponent(heroTitle)}`; return; }
        if (bc.contains("page-trips"))      { location.href = `trips.html?edit=${encodeURIComponent(heroTitle)}`; return; }
        toast("Opening editor…"); return;
      }
    }
  });

  /* ---------- Floating menus (anchored to buttons) ---------- */
  let activeMenu = null;
  function closeMenus() {
    if (activeMenu) { activeMenu.remove(); activeMenu = null; }
  }
  function openMenu(anchor, items, onSelect) {
    closeMenus();
    const menu = document.createElement("div");
    menu.className = "flymenu";
    menu.innerHTML = items.map(it => {
      if (it.sep) return `<div class="flymenu__sep"></div>`;
      return `<button class="flymenu__item ${it.active ? "active" : ""}" data-value="${it.value}">${it.icon ? icon(it.icon,14) : ""}<span>${it.label}</span>${it.shortcut ? `<small>${it.shortcut}</small>` : ""}</button>`;
    }).join("");
    document.body.appendChild(menu);

    // Position below-left of the anchor
    const r = anchor.getBoundingClientRect();
    const menuW = Math.max(menu.offsetWidth, 220);
    const right = Math.max(12, window.innerWidth - r.right);
    menu.style.top = (r.bottom + window.scrollY + 6) + "px";
    menu.style.right = right + "px";
    menu.style.left = "auto";

    requestAnimationFrame(() => menu.classList.add("open"));
    activeMenu = menu;

    menu.addEventListener("click", e => {
      const item = e.target.closest(".flymenu__item");
      if (!item) return;
      e.stopPropagation();
      onSelect(item.dataset.value, item.textContent.trim(), item);
      closeMenus();
    });
    setTimeout(() => {
      document.addEventListener("click", outsideClose, { once: true });
    }, 0);
    function outsideClose(e) {
      if (!menu.contains(e.target) && e.target !== anchor) closeMenus();
      else document.addEventListener("click", outsideClose, { once: true });
    }
  }

  function openDateRangeMenu(anchor) {
    const ranges = [
      { label: "Today",        value: "1d" },
      { label: "Yesterday",    value: "y" },
      { label: "Last 7 days",  value: "7d" },
      { label: "Last 30 days", value: "30d", active: anchor.textContent.includes("30") },
      { label: "Last 90 days", value: "90d" },
      { label: "This month",   value: "mo" },
      { label: "This quarter", value: "qtr" },
      { sep: true },
      { label: "Custom range…", value: "custom", icon: "cal" },
    ];
    openMenu(anchor, ranges, (value, label) => {
      if (value === "custom") { toast("Custom date range picker — coming soon"); return; }
      const labelText = label.replace(/\s+/g," ").trim();
      // Rebuild the button label, preserving the calendar icon
      const svg = anchor.querySelector("svg")?.outerHTML || "";
      anchor.innerHTML = `${svg} ${labelText}`;
      toast(`Date range: ${labelText}`);
    });
  }

  function openQuickAddMenu(anchor) {
    const items = [
      { label: "New Trip",      value: "trip",      icon: "route",  shortcut: "T" },
      { label: "New Truck",     value: "truck",     icon: "truck",  shortcut: "K" },
      { label: "New Driver",    value: "driver",    icon: "user",   shortcut: "D" },
      { label: "New Container", value: "container", icon: "box",    shortcut: "C" },
      { sep: true },
      { label: "Import from CSV…", value: "import", icon: "upload" },
    ];
    openMenu(anchor, items, (value) => {
      const routes = {
        trip:      "trips.html?new=1",
        truck:     "trucks.html?new=1",
        driver:    "drivers.html?new=1",
        container: "containers.html?new=1",
      };
      if (routes[value]) location.href = routes[value];
      else if (value === "import") { triggerFilePicker(); toast("Select a CSV file to import"); }
    });
  }

  function exportCsv() {
    // Build a reasonable CSV from whichever module is on-screen
    let rows = [], filename = "flecso-export.csv";
    const D = window.FlecsoData || {};
    if (document.body.classList.contains("page-trucks") && D.trucks) {
      rows = [["ID","Plate","Category","Capacity","Driver","Mileage","Last Service","Status","Fuel"]];
      D.trucks.forEach(t => rows.push([t.id,t.plate,t.category,t.capacity,t.driver,t.mileage,t.lastService,t.status,t.fuel]));
      filename = "flecso-trucks.csv";
    } else if (document.body.classList.contains("page-containers") && D.containers) {
      rows = [["ID","Type","ISO","Owner","Serial","CheckDigit","Weight","Location","Status"]];
      D.containers.forEach(c => rows.push([c.id,c.type,c.iso,c.owner,c.serial,c.checkDigit,c.weight,c.location,c.status]));
      filename = "flecso-containers.csv";
    } else if (document.body.classList.contains("page-drivers") && D.drivers) {
      rows = [["ID","Name","Phone","Email","License","Expiry","Status","Rating","Trips"]];
      D.drivers.forEach(d => rows.push([d.id,d.name,d.phone,d.email,d.license,d.expiry,d.status,d.rating,d.trips]));
      filename = "flecso-drivers.csv";
    } else if (document.body.classList.contains("page-trips") && D.trips) {
      rows = [["ID","Type","Driver","Truck","Container","From","To","Distance","ETA","Status","Date"]];
      D.trips.forEach(t => rows.push([t.id,t.type,t.driver,t.truck,t.container,t.from,t.to,t.distance,t.eta,t.status,t.date]));
      filename = "flecso-trips.csv";
    } else if (document.body.classList.contains("page-sos") && D.sosAlerts) {
      rows = [["ID","Type","Severity","Status","Driver","Truck","Trip","Location","Raised At","Responder","ETA","Description"]];
      D.sosAlerts.forEach(s => rows.push([s.id, s.type, s.severity, s.status, s.driver, s.truck, s.trip, s.location, s.raisedAt, s.responder, s.etaResponse, s.description]));
      filename = "flecso-sos-log.csv";
    } else {
      // Dashboard / fallback — export a KPI snapshot
      const k = (D.kpis) || { trucks: 128, containers: 342, drivers: 86, activeTrips: 47 };
      rows = [
        ["Metric","Value","Trend %"],
        ["Total Trucks", k.trucks, k.trucksTrend ?? ""],
        ["Total Containers", k.containers, k.containersTrend ?? ""],
        ["Total Drivers", k.drivers, k.driversTrend ?? ""],
        ["Active Trips", k.activeTrips, k.tripsTrend ?? ""],
        ["Completed Trips (last 30d)", 214, ""],
        ["Delayed Trips", 12, ""],
        ["Cancelled Trips", 5, ""],
        ["On-time Rate %", 94.2, ""],
      ];
      filename = "flecso-dashboard.csv";
    }
    const esc = v => {
      const s = String(v ?? "");
      return /[",\n]/.test(s) ? `"${s.replace(/"/g,'""')}"` : s;
    };
    const csv = rows.map(r => r.map(esc).join(",")).join("\n");
    const blob = new Blob([csv], { type: "text/csv;charset=utf-8" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url; a.download = filename;
    document.body.appendChild(a); a.click(); a.remove();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
    toast(`Exported · ${filename}`);
  }

  /* Populate a drawer form field by matching its <label> text */
  function setField(label, value) {
    if (value === undefined || value === null) return;
    const labels = document.querySelectorAll("#drawerBody label");
    for (const l of labels) {
      const txt = l.textContent.replace(/\s+/g, " ").trim().replace(/\s*\*$/, "");
      if (txt === label || txt.startsWith(label)) {
        const input = l.parentElement.querySelector("input, select, textarea");
        if (!input) continue;
        if (input.tagName === "SELECT") {
          // Pick the option whose text matches value (case-insensitive)
          const target = String(value).toLowerCase();
          const match = Array.from(input.options).find(o => o.textContent.trim().toLowerCase() === target)
                      || Array.from(input.options).find(o => o.textContent.trim().toLowerCase().includes(target));
          if (match) input.value = match.value;
        } else {
          input.value = value;
        }
        return;
      }
    }
  }

  /* ---------- Public API ---------- */
  window.Flecso = { icon, statusBadge, field, selectField, uploadBox, openDrawer, closeAll, toast, showQR, setField };
  window.showQR = showQR; // used by inline row buttons

  /* ---------- Auto-open drawer on ?new=1 ---------- */
  if (/[?&]new=1/.test(location.search)) {
    const ids = { "page-trucks":"addTruckBtn", "page-containers":"addContainerBtn", "page-drivers":"addDriverBtn", "page-trips":"addTripBtn" };
    const cls = Object.keys(ids).find(c => document.body.classList.contains(c));
    if (cls) setTimeout(() => document.getElementById(ids[cls])?.click(), 120);
  }
})();
