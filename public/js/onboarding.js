/* =========================================================
   Flecso — 4-step new-account onboarding
   Mirrors the Laravel API:
     POST /profile/step1  (Company info)
     POST /profile/step2  (Address & comms)
     POST /profile/step3  (Operations & fleet)
     POST /profile/step4  (Legal representative + document)
     GET  /profile        (returns current_step)
   ========================================================= */
(function () {
  const $ = (s, r=document) => r.querySelector(s);
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

  const STORAGE_KEY = "flecso.onboarding";
  const TOTAL_STEPS = 4;

  /* Validation rules per the Laravel back-end */
  const RULES = {
    1: {
      company_legal_name: { req: true,  type: "string" },
      company_type:       { req: true,  type: "string" },
      vat_number:         { req: true,  type: "digits", len: 11, msg: "Partita IVA must be exactly 11 digits" },
      fiscal_code:        { req: true,  type: "string" },
      rea_number:         { req: true,  type: "string" },
    },
    2: {
      pec_email:          { req: true,  type: "email" },
      sdi_code:           { req: true,  type: "alnum", len: 7,  msg: "SDI code must be exactly 7 characters" },
      registered_address: { req: true,  type: "string" },
      city:               { req: true,  type: "string" },
      province:           { req: true,  type: "alpha", len: 2,  msg: "Province must be a 2-letter code (e.g. MI)", upper: true },
      zip_code:           { req: true,  type: "string" },
    },
    3: {
      ren_number:              { req: true,  type: "string" },
      eu_license_number:       { req: false, type: "string" },
      fleet_trucks:            { req: true,  type: "int", min: 0 },
      fleet_vans:              { req: true,  type: "int", min: 0 },
      fleet_containers:        { req: true,  type: "int", min: 0 },
      insurance_policy_number: { req: true,  type: "string" },
    },
    4: {
      rep_full_name:    { req: true,  type: "string" },
      rep_position:     { req: true,  type: "string" },
      rep_fiscal_code:  { req: true,  type: "alnum", len: 16, msg: "Codice Fiscale must be exactly 16 characters", upper: true },
      rep_document:     { req: true,  type: "file",  exts: ["jpg","jpeg","png","pdf"], maxMB: 5 },
    }
  };

  /* ---------- State ---------- */
  let currentStep = Number(new URLSearchParams(location.search).get("step")) || 1;
  if (currentStep < 1 || currentStep > TOTAL_STEPS) currentStep = 1;

  const state = loadState();

  /* Restore values into the form on init */
  function restoreInputs() {
    Object.entries(state).forEach(([key, value]) => {
      const el = $(`[name="${key}"]`);
      if (!el) return;
      if (el.type === "file") return; // can't restore file inputs
      el.value = value ?? "";
    });
    // Update file UI if a previous file name was saved
    if (state.__rep_document_name) {
      markUploadFile(state.__rep_document_name);
    }
  }

  function loadState() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}"); }
    catch (e) { return {}; }
  }
  function saveState() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  }

  /* ---------- Stepper ---------- */
  function renderStepper() {
    $$(".onb-step").forEach((el, i) => {
      const idx = i + 1;
      el.classList.toggle("active", idx === currentStep);
      el.classList.toggle("done",   idx <  currentStep);
    });
    // progress meta
    const meta = $("#onbProgress");
    if (meta) meta.innerHTML = `Step <strong>${currentStep}</strong> of ${TOTAL_STEPS}`;
    const stepMeta = $(".stepmeta");
    if (stepMeta) stepMeta.textContent = `STEP ${currentStep} OF ${TOTAL_STEPS}`;
    updateTrack();
  }

  /* Measure circle positions and write them as CSS variables on the
     stepper so the ::before track and ::after progress line span exactly
     between circle centers, regardless of padding, font or viewport. */
  function updateTrack() {
    const stepper = $(".onb-stepper");
    if (!stepper) return;
    const circles = $$(".onb-step__num", stepper);
    if (circles.length < 2) return;
    const sBox = stepper.getBoundingClientRect();
    const fBox = circles[0].getBoundingClientRect();
    const lBox = circles[circles.length - 1].getBoundingClientRect();
    const aBox = circles[Math.max(0, Math.min(currentStep - 1, circles.length - 1))].getBoundingClientRect();
    const start  = (fBox.left + fBox.width / 2) - sBox.left;
    const end    = (lBox.left + lBox.width / 2) - sBox.left;
    const active = (aBox.left + aBox.width / 2) - sBox.left;
    const y      = (fBox.top  + fBox.height / 2) - sBox.top - 1; // -1 for the 2px line height
    stepper.style.setProperty("--track-start",  start  + "px");
    stepper.style.setProperty("--track-end",    end    + "px");
    stepper.style.setProperty("--track-active", active + "px");
    stepper.style.setProperty("--track-y",      y      + "px");
  }
  // Recalculate when the viewport changes
  window.addEventListener("resize", updateTrack);

  const HEADINGS = {
    1: { h: "Company information",      s: "Legal entity details — exactly as registered with the Camera di Commercio." },
    2: { h: "Address & communication",  s: "Registered office and Italian e-invoicing endpoints." },
    3: { h: "Operations & fleet",       s: "Licensing details and the size of your operation today." },
    4: { h: "Legal representative",     s: "Identity verification of the company's legal representative." },
  };

  function showStep(n) {
    currentStep = n;
    $$(".onb-pane").forEach(p => p.classList.toggle("active", Number(p.dataset.step) === n));
    $$(".onb-side-pane").forEach(p => p.classList.toggle("active", Number(p.dataset.step) === n));
    renderStepper();
    // Heading + subtitle
    const h = HEADINGS[n];
    if (h) {
      $("#onbHeading").textContent = h.h;
      $("#onbSub").textContent = h.s;
    }
    // Update URL without reload
    const url = new URL(location.href);
    url.searchParams.set("step", n);
    history.replaceState(null, "", url);
    // Update buttons
    $("#onbBack").disabled = n === 1;
    const btn = $("#onbContinue");
    btn.querySelector(".label").textContent = n === TOTAL_STEPS ? "Submit & Finish" : "Continue";
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  /* ---------- Validation ---------- */
  function validateStep(n) {
    const rules = RULES[n];
    const data = {};
    let firstError = null;

    Object.entries(rules).forEach(([name, rule]) => {
      const field = $(`[data-field="${name}"]`);
      if (!field) return;
      field.classList.remove("has-error");

      if (rule.type === "file") {
        const input = field.querySelector("input[type=file]");
        const file = input.files[0];
        if (rule.req && !file) return setError(field, "Please upload a document", name);
        if (file) {
          const ext = file.name.split(".").pop().toLowerCase();
          if (!rule.exts.includes(ext)) return setError(field, `Allowed: ${rule.exts.join(", ").toUpperCase()}`, name);
          if (file.size > rule.maxMB * 1024 * 1024) return setError(field, `Max ${rule.maxMB} MB`, name);
          data[name] = file;
          state.__rep_document_name = file.name;
        }
        return;
      }

      const input = field.querySelector("input, select, textarea");
      let v = (input.value || "").trim();
      if (rule.upper) v = v.toUpperCase();

      if (rule.req && !v) return setError(field, "This field is required", name);
      if (!v && !rule.req) { data[name] = ""; return; }

      if (rule.type === "digits"   && !/^\d+$/.test(v))                   return setError(field, rule.msg || "Numbers only", name);
      if (rule.type === "alpha"    && !/^[A-Za-z]+$/.test(v))             return setError(field, rule.msg || "Letters only", name);
      if (rule.type === "alnum"    && !/^[A-Za-z0-9]+$/.test(v))          return setError(field, rule.msg || "Letters and numbers only", name);
      if (rule.type === "email"    && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) return setError(field, "Enter a valid email", name);
      if (rule.type === "int"      && !/^\d+$/.test(v))                   return setError(field, "Whole numbers only", name);
      if (rule.len  != null        && v.length !== rule.len)              return setError(field, rule.msg || `Must be ${rule.len} characters`, name);
      if (rule.min  != null && rule.type === "int" && Number(v) < rule.min) return setError(field, `Minimum ${rule.min}`, name);

      input.value = v; // write normalised value back
      data[name] = v;
    });

    function setError(field, msg, name) {
      field.classList.add("has-error");
      const hint = field.querySelector(".onb-field__hint");
      if (hint) hint.textContent = msg;
      if (!firstError) firstError = field;
    }

    if (firstError) {
      firstError.scrollIntoView({ behavior: "smooth", block: "center" });
      firstError.querySelector("input, select, textarea")?.focus({ preventScroll: true });
      return null;
    }
    return data;
  }

  /* ---------- Mock API submit ---------- */
  function submitStep(n, data) {
    return new Promise(resolve => {
      // Simulate latency. In production replace with fetch() calls to:
      //   /api/profile/step{n}
      console.log(`[mock] POST /profile/step${n}`, data);
      setTimeout(() => resolve({ status: true, message: `Step ${n} completed`, data }), 700);
    });
  }

  /* ---------- File upload UI ---------- */
  function markUploadFile(name) {
    const drop = $(".onb-upload");
    if (!drop) return;
    drop.classList.add("has-file");
    let chip = drop.querySelector(".onb-upload__file");
    const html = `
      <div class="onb-upload__file">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
        <span>${name}</span>
        <button type="button" id="removeFile" aria-label="Remove">×</button>
      </div>`;
    if (chip) chip.outerHTML = html;
    else drop.insertAdjacentHTML("beforeend", html);
    $("#removeFile")?.addEventListener("click", e => {
      e.stopPropagation();
      drop.classList.remove("has-file");
      drop.querySelector("input[type=file]").value = "";
      drop.querySelector(".onb-upload__file")?.remove();
      delete state.__rep_document_name;
      saveState();
    });
  }

  /* ---------- Wire-up ---------- */
  document.addEventListener("DOMContentLoaded", init);
  if (document.readyState !== "loading") init();

  function init() {
    if (!$(".onb")) return;

    restoreInputs();
    showStep(currentStep);

    // Continue
    $("#onbContinue").addEventListener("click", async () => {
      const data = validateStep(currentStep);
      if (!data) return;

      // Persist (skip the File object — only its name)
      Object.entries(data).forEach(([k, v]) => {
        if (v instanceof File) state.__rep_document_name = v.name;
        else state[k] = v;
      });
      saveState();

      const btn = $("#onbContinue");
      btn.classList.add("loading");
      btn.disabled = true;
      try {
        await submitStep(currentStep, data);
      } finally {
        btn.classList.remove("loading");
        btn.disabled = false;
      }

      if (currentStep < TOTAL_STEPS) {
        showStep(currentStep + 1);
      } else {
        showSuccess();
      }
    });

    // Back
    $("#onbBack").addEventListener("click", () => {
      if (currentStep > 1) showStep(currentStep - 1);
    });

    // Persist on input so refresh keeps the data
    $$(".onb-pane input, .onb-pane select, .onb-pane textarea").forEach(el => {
      if (el.type === "file") {
        el.addEventListener("change", () => {
          const f = el.files[0];
          if (f) markUploadFile(f.name);
        });
        return;
      }
      el.addEventListener("input", () => {
        const name = el.name;
        if (name) { state[name] = el.value; saveState(); }
      });
    });

    // Fleet stepper buttons
    $$(".fleet-stepper").forEach(s => {
      const inp = s.querySelector("input");
      s.querySelector("[data-act=dec]").addEventListener("click", () => {
        const v = Math.max(0, (parseInt(inp.value || 0, 10) - 1));
        inp.value = v; inp.dispatchEvent(new Event("input"));
      });
      s.querySelector("[data-act=inc]").addEventListener("click", () => {
        const v = (parseInt(inp.value || 0, 10) + 1);
        inp.value = v; inp.dispatchEvent(new Event("input"));
      });
    });

    // Save & exit / brand
    $("#onbExit")?.addEventListener("click", () => {
      saveState();
      // In production: keep partial data and bounce out to dashboard or login
      location.href = "login.html";
    });

    // Click on stepper to jump back to a completed step
    $$(".onb-step").forEach((el, i) => {
      el.addEventListener("click", () => {
        const idx = i + 1;
        if (idx < currentStep) showStep(idx);
      });
    });
  }

  function showSuccess() {
    localStorage.removeItem(STORAGE_KEY);
    const main = $(".onb-main");
    main.innerHTML = `
      <div class="onb-success">
        <div class="onb-success__icon">
          <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <path d="M22 4 12 14.01l-3-3"/>
          </svg>
        </div>
        <h2>You're all set, welcome to Flecso 🎉</h2>
        <p>Your account profile is complete. Our team will verify the documents within 1 business day — meanwhile you have full access to the dashboard.</p>
        <a class="onb-success__btn" href="index.html">
          Go to Dashboard
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>`;
    // Mark all stepper items done
    $$(".onb-step").forEach(s => { s.classList.add("done"); s.classList.remove("active"); });
    $(".onb-footer")?.remove();
  }
})();
