/**
 * AJAX Form Handler for Toast Notifications
 * Handles form submissions with AJAX and displays toast notifications
 * 
 * Usage:
 * <form class="ajax-form" method="POST" action="/path">
 *     ... form fields ...
 * </form>
 */

document.addEventListener('DOMContentLoaded', function() {
    const ajaxForms = document.querySelectorAll('.ajax-form');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.getAttribute('action');
            const method = this.getAttribute('method') || 'POST';
            
            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
            }
            
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        return { success: response.ok, message: 'Operation completed' };
                    }
                });
            })
            .then(data => {
                // Show toast notification
                if (data.success) {
                    showToast(data.message || 'Success!', 'success');
                    // Reset form
                    form.reset();

                    // If server returned updated station info, update the UI in-place
                    if (data.station_id && data.new_status) {
                        // find the row containing this form
                        const row = form.closest('tr');
                        if (row) {
                            const badge = row.querySelector('td span');
                            if (badge) {
                                // remove existing status classes
                                badge.classList.remove('bg-green-600', 'bg-red-600', 'bg-yellow-500');
                                // add new class based on status
                                if (data.new_status === 'active') badge.classList.add('bg-green-600');
                                else if (data.new_status === 'inactive') badge.classList.add('bg-red-600');
                                else if (data.new_status === 'maintenance') badge.classList.add('bg-yellow-500');
                                // update text
                                badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                            }
                        }
                    }

                    // Optional: Redirect if provided
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                } else {
                    showToast(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                showToast(error.message || 'Network error', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            });
        });
    });
});

/**
 * Fetch helper for AJAX requests with toast notifications
 * Usage: ajaxRequest('POST', '/admin/stations/store', formData)
 */
function ajaxRequest(method, url, data = null) {
    return fetch(url, {
        method: method,
        body: data instanceof FormData ? data : JSON.stringify(data),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': data instanceof FormData ? undefined : 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Success!', 'success');
        } else {
            showToast(data.message || 'An error occurred', 'error');
        }
        return data;
    })
    .catch(error => {
        showToast('Network error: ' + error.message, 'error');
        throw error;
    });
}
