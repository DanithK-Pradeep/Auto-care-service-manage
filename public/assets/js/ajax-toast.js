/**
 * AJAX + Toast + UI Utility System (Final Fixed Version)
 * Handles: Toasts, Flash Messages, AJAX Forms, and HTMX Compatibility
 */

/* ---------- 1. Automatic CSS & Container Injection ---------- */
(function setupToastEnvironment() {
    if (!document.getElementById("ajaxToastStyles")) {
        const style = document.createElement("style");
        style.id = "ajaxToastStyles";
        style.textContent = `
            .toast-container { position: fixed; bottom: 20px; left: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
            .toast { min-width: 300px; max-width: 450px; padding: 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; animation: slideInLeft 0.3s ease-out; font-size: 14px; font-weight: 500; }
            @keyframes slideInLeft { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            .toast.success { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
            .toast.error   { background:#F8D7DA; color:#721C24; border:1px solid #F5C6CB; }
            .toast.warning { background:#FFF3CD; color:#856404; border:1px solid #FFEEBA; }
            .toast.info    { background:#D1ECF1; color:#0C5460; border:1px solid #BEE5EB; }
            .toast-close { margin-left:auto; background:none; border:none; cursor:pointer; font-size:18px; opacity:0.7; }
        `;
        document.head.appendChild(style);
    }

    const injectContainer = () => {
        if (!document.getElementById("toastContainer") && document.body) {
            const container = document.createElement("div");
            container.id = "toastContainer";
            container.className = "toast-container";
            document.body.appendChild(container);
        }
    };
    if (document.body) injectContainer(); else document.addEventListener("DOMContentLoaded", injectContainer);
})();

/* ---------- 2. Global Helpers ---------- */
window.esc = (t) => { const d = document.createElement("div"); d.textContent = String(t ?? ""); return d.innerHTML; };

window.showToast = function (message, type = "success") {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span>${window.esc(message)}</span><button onclick="this.parentElement.remove()" style="margin-left:auto; cursor:pointer;">×</button>`;
    
    container.appendChild(toast);
    if (container.children.length > 5) container.removeChild(container.firstChild);
    setTimeout(() => { if (toast.parentElement) toast.remove(); }, 4000);
};

/* ---------- 3. AJAX Logic ---------- */
window.ajaxRequest = async function (method, url, data = null) {
    try {
        const res = await fetch(url, {
            method: method.toUpperCase(),
            body: data instanceof FormData ? data : data ? JSON.stringify(data) : null,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        const resp = await res.json();
        window.showToast(resp.message, resp.success ? "success" : "error");
        return resp;
    } catch (e) {
        window.showToast("Network error", "error");
    }
};

/* ---------- 4. AJAX Form Handler ---------- */
document.addEventListener("submit", async function (e) {
    const form = e.target.closest(".ajax-form");
    if (!form) return;

    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if (btn) btn.disabled = true;

    try {
        const data = await window.ajaxRequest(form.method || "POST", form.action, new FormData(form));
        if (data.success && data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1000);
        }
    } finally {
        if (btn) btn.disabled = false;
    }
});

/* ---------- 5. Flash Message Listener (Crucial for Login/Logout) ---------- */
document.addEventListener("DOMContentLoaded", function () {
    // Check if there are any messages from the server (session flashdata)
    if (window.__flash) {
        Object.entries(window.__flash).forEach(([type, msg]) => {
            if (msg && typeof msg === 'string') {
                // If CodeIgniter uses 'message' as key, default to success
                window.showToast(msg, type === 'message' ? 'success' : type);
            }
        });
    }
});

/* ---------- 6. Attendance Functions ---------- */
window.processCheckIn = async () => {
    const res = await window.ajaxRequest("POST", "/employee/attendance/checkIn");
    if (res.success) setTimeout(() => location.reload(), 1000);
};

window.confirmCheckOut = () => {
    const m = document.getElementById("checkOutModal");
    if (m) { m.classList.remove("hidden"); m.classList.add("flex"); }
};

window.processCheckOut = async () => {
    const res = await window.ajaxRequest("POST", "/employee/attendance/checkOut");
    if (res.success) setTimeout(() => location.reload(), 1000);
};

window.processCheckOut = async () => {
  const res = await window.ajaxRequest("POST", "/employee/attendance/checkOut");
  if (res.success) setTimeout(() => location.reload(), 1000);
};

// ---------- 7. HTMX Integration for Active Link Highlighting ----------

document.addEventListener("htmx:afterSettle", function (evt) {

  const currentUrl = window.location.href.split("?")[0].replace(/\/$/, "");
  const sidebarLinks = document.querySelectorAll(".nav-link");

  sidebarLinks.forEach((link) => {
    const linkUrl = link.href.split("?")[0].replace(/\/$/, "");


    link.classList.remove(
      "bg-blue-600",
      "bg-green-600",
      "text-white",
      "shadow-lg",
    );
    link.classList.add("text-white/80", "hover:bg-white/20");

    
    if (currentUrl === linkUrl) {
      const isActiveSupervisor =
        link.href.includes("supervisor") && !link.href.includes("history");
      const activeClass = isActiveSupervisor ? "bg-green-600" : "bg-blue-600";

      link.classList.add(activeClass, "text-white", "shadow-lg");
      link.classList.remove("text-white/80", "hover:bg-white/20");
    }
  });
});



