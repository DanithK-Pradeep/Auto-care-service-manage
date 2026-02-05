<?php
// Toast notification component for displaying messages
// Usage: <?= view('admin/components/toast') ?>


<style>
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInLeft 0.3s ease-out, slideOutLeft 0.3s ease-out 3.7s forwards;
        font-size: 14px;
        font-weight: 500;
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutLeft {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-400px);
            opacity: 0;
        }
    }

    .toast.success {
        background-color: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .toast.error {
        background-color: #F8D7DA;
        color: #721C24;
        border: 1px solid #F5C6CB;
    }

    .toast.warning {
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEEBA;
    }

    .toast.info {
        background-color: #D1ECF1;
        color: #0C5460;
        border: 1px solid #BEE5EB;
    }

    .toast-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-icon svg {
        width: 100%;
        height: 100%;
    }

    .toast-close {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .toast-close:hover {
        opacity: 1;
    }
</style>

<div class="toast-container" id="toastContainer"></div>

<script>
    // Show toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        
        // SVG icons for different toast types
        const icons = {
            success: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
            warning: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            info: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                ${icons[type] || icons.success}
            </div>
            <span>${esc(message)}</span>
            <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 4000);
    }

    // Utility function to escape HTML
    function esc(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Show flash data if exists
    document.addEventListener('DOMContentLoaded', function() {
        const successMsg = '<?= esc(session()->getFlashdata('success') ?? '') ?>';
        const errorMsg = '<?= esc(session()->getFlashdata('error') ?? '') ?>';
        const warningMsg = '<?= esc(session()->getFlashdata('warning') ?? '') ?>';
        const activeMsg = '<?= esc(session()->getFlashdata('active') ?? '') ?>';
        const inactiveMsg = '<?= esc(session()->getFlashdata('inactive') ?? '') ?>';

        if (successMsg && successMsg.trim()) {
            showToast(successMsg, 'success');
        }
        if (errorMsg && errorMsg.trim()) {
            showToast(errorMsg, 'error');
        }
        if (warningMsg && warningMsg.trim()) {
            showToast(warningMsg, 'warning');
        }
        if (activeMsg && activeMsg.trim()) {
            showToast(activeMsg, 'success');
        }
        if (inactiveMsg && inactiveMsg.trim()) {
            showToast(inactiveMsg, 'warning');
        }
    });
</script>
