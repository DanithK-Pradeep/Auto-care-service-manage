/* =========================================================
   AJAX + Toast Utility (One file)
   - Handles .ajax-form submissions with fetch (AJAX)
   - Shows toast notifications (success/error/warning/info)
   - Optional UI update hook for station status badge
   - Optional redirect support: { redirect: "/path" }
========================================================= */

/* ---------- Toast Styles (Injected once) ---------- */
(function injectToastStyles() {
  if (document.getElementById("ajaxToastStyles")) return;

  const style = document.createElement("style");
  style.id = "ajaxToastStyles";
  style.textContent = `
    .toast-container {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .toast {
      min-width: 300px;
      max-width: 450px;
      padding: 16px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideInLeft 0.3s ease-out, slideOutLeft 0.3s ease-out 3.7s forwards;
      font-size: 14px;
      font-weight: 500;
    }
    @keyframes slideInLeft {
      from { transform: translateX(-400px); opacity: 0; }
      to   { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutLeft {
      from { transform: translateX(0); opacity: 1; }
      to   { transform: translateX(-400px); opacity: 0; }
    }
    .toast.success { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
    .toast.error   { background:#F8D7DA; color:#721C24; border:1px solid #F5C6CB; }
    .toast.warning { background:#FFF3CD; color:#856404; border:1px solid #FFEEBA; }
    .toast.info    { background:#D1ECF1; color:#0C5460; border:1px solid #BEE5EB; }

    .toast-icon { flex-shrink:0; width:20px; height:20px; display:flex; align-items:center; justify-content:center; }
    .toast-icon svg { width:100%; height:100%; }
    .toast-close {
      margin-left:auto;
      background:none;
      border:none;
      cursor:pointer;
      font-size:18px;
      padding:0;
      display:flex;
      align-items:center;
      justify-content:center;
      opacity:0.7;
      transition:opacity 0.2s;
    }
    .toast-close:hover { opacity:1; }
  `;
  document.head.appendChild(style);
})();

/* ---------- Toast Container (Injected once) ---------- */
(function ensureToastContainer() {
  if (document.getElementById("toastContainer")) return;
  const container = document.createElement("div");
  container.className = "toast-container";
  container.id = "toastContainer";
  document.body.appendChild(container);
})();

/* ---------- Helpers ---------- */
function esc(text) {
  const div = document.createElement("div");
  div.textContent = String(text ?? "");
  return div.innerHTML;
}

function showToast(message, type = "success") {
  const container = document.getElementById("toastContainer");
  if (!container) return;

  const icons = {
    success: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
    error:   '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
    warning: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    info:    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
  };

  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <div class="toast-icon">${icons[type] || icons.success}</div>
    <span>${esc(message)}</span>
    <button class="toast-close" aria-label="Close">×</button>
  `;

  toast.querySelector(".toast-close")?.addEventListener("click", () => toast.remove());
  container.appendChild(toast);

  // Keep max 5 toasts
  while (container.children.length > 5) container.removeChild(container.firstChild);

  setTimeout(() => {
    if (toast.parentElement) toast.remove();
  }, 4000);
}

// Expose globally (so you can call showToast anywhere)
window.showToast = showToast;

/* ---------- Safe JSON parsing (handles HTML responses too) ---------- */
async function parseResponseAsJsonSafe(response) {
  const contentType = response.headers.get("content-type") || "";
  if (contentType.includes("application/json")) return response.json();

  const text = await response.text();
  try {
    return JSON.parse(text);
  } catch {
    return { success: response.ok, message: "Operation completed" };
  }
}

/* ---------- AJAX Form Handler (.ajax-form) ---------- */
document.addEventListener("DOMContentLoaded", function () {
  const ajaxForms = document.querySelectorAll(".ajax-form");

  ajaxForms.forEach((form) => {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(form);
      const url = form.getAttribute("action");
      const method = (form.getAttribute("method") || "POST").toUpperCase();

      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      try {
        const res = await fetch(url, {
          method,
          body: formData,
          headers: { "X-Requested-With": "XMLHttpRequest" },
        });

        const data = await parseResponseAsJsonSafe(res);

        if (data.success) {
          showToast(data.message || "Success!", "success");

          // Optional: Reset form
          if (!form.dataset.noReset) form.reset();

          // Optional: Update station status badge in the same row
          // Expected response: { station_id, new_status }
          if (data.station_id && data.new_status) {
            const row = form.closest("tr");
            if (row) {
              const badge = row.querySelector("td span");
              if (badge) {
                badge.classList.remove("bg-green-600", "bg-red-600", "bg-yellow-500");
                if (data.new_status === "active") badge.classList.add("bg-green-600");
                else if (data.new_status === "inactive") badge.classList.add("bg-red-600");
                else if (data.new_status === "maintenance") badge.classList.add("bg-yellow-500");

                badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
              }
            }
          }

          // Optional redirect
          if (data.redirect) {
            setTimeout(() => (window.location.href = data.redirect), 800);
          }

          // Optional: close modal if you send "closeModalId"
          if (data.closeModalId) {
            const modal = document.getElementById(data.closeModalId);
            if (modal) {
              modal.classList.add("hidden");
              modal.classList.remove("flex");
            }
          }
        } else {
          showToast(data.message || "An error occurred", "error");
        }
      } catch (error) {
        showToast(error.message || "Network error", "error");
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  });

  // Flash messages support (set window.__flash in your PHP view)
  if (window.__flash) {
    const { success, error, warning, info } = window.__flash;
    if (success) showToast(success, "success");
    if (error) showToast(error, "error");
    if (warning) showToast(warning, "warning");
    if (info) showToast(info, "info");
  }
});

/* ---------- Helper for custom AJAX requests ---------- */
window.ajaxRequest = function ajaxRequest(method, url, data = null) {
  const isFormData = data instanceof FormData;

  return fetch(url, {
    method: method.toUpperCase(),
    body: isFormData ? data : data ? JSON.stringify(data) : null,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      ...(isFormData ? {} : { "Content-Type": "application/json" }),
    },
  })
    .then((res) => parseResponseAsJsonSafe(res))
    .then((data) => {
      if (data.success) showToast(data.message || "Success!", "success");
      else showToast(data.message || "An error occurred", "error");
      return data;
    })
    .catch((error) => {
      showToast("Network error: " + error.message, "error");
      throw error;
    });
};
