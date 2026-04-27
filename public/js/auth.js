/* =========================================================
   Auth Screens — shared form behavior
   (password visibility toggle, validation, submit, strength meter)
   ========================================================= */
(function () {
  const $ = (s, r=document) => r.querySelector(s);
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

  /* Password visibility toggle */
  $$(".auth-input__btn[data-toggle-pw]").forEach(btn => {
    btn.addEventListener("click", () => {
      const input = btn.parentElement.querySelector("input");
      const showing = input.type === "text";
      input.type = showing ? "password" : "text";
      btn.innerHTML = showing
        ? `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>`
        : `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>`;
    });
  });

  /* Password strength meter */
  const pwInput = $("#password");
  const meter   = $(".pw-strength");
  const meterLbl = $(".pw-strength-label");
  if (pwInput && meter) {
    pwInput.addEventListener("input", () => {
      const v = pwInput.value;
      let score = 0;
      if (v.length >= 8) score++;
      if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
      if (/\d/.test(v)) score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;
      const levels = ["", "weak", "fair", "good", "strong"];
      const labels = ["Use at least 8 characters", "Weak password", "Fair — add a number or symbol", "Good — add a symbol for extra security", "Strong password"];
      meter.className = "pw-strength " + levels[score];
      if (meterLbl) meterLbl.textContent = v ? labels[score] : "Use at least 8 characters, mix of letters, numbers, symbols";
    });
  }

  /* Form validation + fake submit */
  $$(".auth-form").forEach(form => {
    form.addEventListener("submit", e => {
      e.preventDefault();
      let valid = true;

      // Clear previous errors
      $$(".auth-field.has-error", form).forEach(f => f.classList.remove("has-error"));

      // Email validation
      const email = form.querySelector("input[type=email]");
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        email.closest(".auth-field").classList.add("has-error");
        const hint = email.closest(".auth-field").querySelector(".auth-field__hint");
        if (hint) hint.textContent = "Please enter a valid email address";
        valid = false;
      }

      // Password validation (only when required)
      const pw = form.querySelector("input[type=password]#password, input[type=text]#password");
      if (pw && pw.value.length < 8) {
        pw.closest(".auth-field").classList.add("has-error");
        const hint = pw.closest(".auth-field").querySelector(".auth-field__hint");
        if (hint) hint.textContent = "Password must be at least 8 characters";
        valid = false;
      }

      // Agreement checkbox on signup
      const agree = form.querySelector("#agree");
      if (agree && !agree.checked) {
        const wrap = agree.closest("label") || agree.closest(".auth-field");
        if (wrap) wrap.style.color = "var(--danger-700)";
        valid = false;
      }

      if (!valid) return;

      // Simulate submit
      const btn = form.querySelector(".auth-submit");
      const origLabel = btn.querySelector(".label").textContent;
      btn.classList.add("loading");
      btn.disabled = true;

      setTimeout(() => {
        btn.classList.remove("loading");
        btn.disabled = false;

        const mode = form.dataset.auth;
        if (mode === "forgot") {
          // Swap to success state
          const card = form.closest(".auth-card");
          const emailVal = email?.value || "your email";
          card.innerHTML = `
            <div class="auth-success">
              <div class="auth-success__icon">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="4" width="20" height="16" rx="2"/>
                  <path d="m22 6-10 7L2 6"/>
                </svg>
              </div>
              <h2>Check your email</h2>
              <p>We've sent a password reset link to <strong>${emailVal}</strong>. It'll expire in 15 minutes.</p>
              <a href="login.html" class="auth-submit" style="max-width:240px;margin:0 auto;display:inline-flex;text-decoration:none">
                <span class="label">Back to sign in</span>
              </a>
              <p style="margin-top:18px;font-size:12.5px">Didn't receive it? <a href="#" id="resend" style="color:var(--orange-600);font-weight:600">Resend link</a></p>
            </div>`;
          card.querySelector("#resend")?.addEventListener("click", (e) => {
            e.preventDefault();
            const t = document.getElementById("toast");
            t.textContent = "Reset link resent to " + emailVal;
            t.classList.add("show");
            setTimeout(() => t.classList.remove("show"), 2400);
          });
        } else if (mode === "signup") {
          location.href = "index.html";
        } else {
          // login
          location.href = "index.html";
        }
      }, 900);
    });
  });

  /* Social buttons */
  $$(".auth-social-btn").forEach(b => {
    b.addEventListener("click", () => {
      const t = document.getElementById("toast");
      t.textContent = `Connecting with ${b.dataset.provider}…`;
      t.classList.add("show");
      setTimeout(() => {
        t.classList.remove("show");
        setTimeout(() => location.href = "index.html", 200);
      }, 900);
    });
  });
})();
