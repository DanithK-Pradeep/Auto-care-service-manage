/**
 * Global AJAX Form Handler & Request Utility
 * Automatically handles any form with the class ".ajax-form"
 * Required: showToast() must be defined globally.
 */

/* ---------- Helper: Safe JSON Parsing ---------- */
async function parseResponseAsJsonSafe(response) {
    const contentType = response.headers.get("content-type") || "";
    if (contentType.includes("application/json")) return response.json();

    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch {
        // Fallback for non-JSON responses
        return { success: response.ok, message: "Server response received" };
    }
}

/* ---------- Main: Form Submission Interceptor ---------- */
document.addEventListener("DOMContentLoaded", function () {
    const ajaxForms = document.querySelectorAll(".ajax-form");

    ajaxForms.forEach((form) => {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const url = form.getAttribute("action");
            const method = (form.getAttribute("method") || "POST").toUpperCase();
            const submitBtn = form.querySelector('button[type="submit"]');

            // Prevent double-submissions
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
                    form.reset();

                    /**
                     * UI Update Hook: Updates status badges in tables
                     * Expected backend keys: { station_id, new_status }
                     */
                    if (data.station_id && data.new_status) {
                        const row = form.closest("tr");
                        const badge = row?.querySelector("td span");
                        if (badge) {
                            // Reset color classes before applying new ones
                            badge.classList.remove("bg-green-600", "bg-red-600", "bg-yellow-500");
                            
                            const statusMap = {
                                active: "bg-green-600",
                                inactive: "bg-red-600",
                                maintenance: "bg-yellow-500"
                            };

                            if (statusMap[data.new_status]) {
                                badge.classList.add(statusMap[data.new_status]);
                            }
                            
                            badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                        }
                    }

                    // Optional Redirect (e.g., after login or delete)
                    if (data.redirect) {
                        setTimeout(() => (window.location.href = data.redirect), 1000);
                    }
                } else {
                    showToast(data.message || "Operation failed", "error");
                }
            } catch (error) {
                showToast("Network error: " + error.message, "error");
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    });
});

/**
 * Manual Request Helper
 * Use this for clicks or script-based triggers (e.g., Delete buttons)
 * Usage: ajaxRequest('GET', '/api/data').then(data => console.log(data));
 */
window.ajaxRequest = async function (method, url, data = null) {
    const isFormData = data instanceof FormData;
    
    try {
        const options = {
            method: method.toUpperCase(),
            headers: { "X-Requested-With": "XMLHttpRequest" }
        };

        if (data) {
            options.body = isFormData ? data : JSON.stringify(data);
            if (!isFormData) options.headers["Content-Type"] = "application/json";
        }

        const res = await fetch(url, options);
        const responseData = await parseResponseAsJsonSafe(res);

        if (responseData.success) {
            showToast(responseData.message || "Done!", "success");
        } else {
            showToast(responseData.message || "Error", "error");
        }

        return responseData;
    } catch (error) {
        showToast("Network error", "error");
        throw error;
    }
};