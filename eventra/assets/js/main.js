// Eventra Main JavaScript - Vanilla JS

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initForms();
    initAJAX();
    initMobileMenu();
});

// Initialize Forms Validation
function initForms() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', validateForm);
    });
}

// Form Validation
function validateForm(e) {
    const form = e.target;
    const email = form.querySelector('#email');
    const password = form.querySelector('#password');
    const confirmPass = form.querySelector('#confirm_password');
    const nameField = form.querySelector('#name');
    
    if (email && !isValidEmail(email.value)) {
        showAlert('Please enter a valid email', 'error');
        e.preventDefault();
        return false;
    }
    
    if (password && password.value.length < 6) {
        showAlert('Password must be at least 6 characters', 'error');
        e.preventDefault();
        return false;
    }
    
    if (confirmPass && password.value !== confirmPass.value) {
        showAlert('Passwords do not match', 'error');
        e.preventDefault();
        return false;
    }
    
    if (nameField && nameField.value.trim().length < 2) {
        showAlert('Name must be at least 2 characters', 'error');
        e.preventDefault();
        return false;
    }
}

// Email Validation
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Show Alert
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existing = document.querySelector('.alert');
    if (existing) existing.remove();
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
    
    document.body.appendChild(alert);
    
    // Auto remove
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// AJAX Functions
function initAJAX() {
    // Book Event AJAX
    const bookBtns = document.querySelectorAll('.book-btn');
    bookBtns.forEach(btn => {
        btn.addEventListener('click', bookEvent);
    });
}

async function bookEvent(e) {
    e.preventDefault();
    const btn = e.target;
    const eventId = btn.dataset.eventId;
    
    if (!eventId) return showAlert('Event not found', 'error');
    
    // Redirect to payment page
    window.location.href = `payment.php?event_id=${eventId}`;
}

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const navLinks = document.getElementById('nav-links');
    
    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
}

// Admin Confirm Delete
function confirmDelete(id, type) {
    const message = `Are you sure you want to delete this ${type}?`;
    if (confirm(message)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}

// Image Preview
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input && preview) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Format Date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Event Cancellation
document.addEventListener('DOMContentLoaded', function() {
    // Cancel booking confirmation
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to cancel this booking? Refund will be initiated if eligible (24h before event).')) {
                const bookingId = this.dataset.bookingId;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'cancel_booking.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'booking_id';
                input.value = bookingId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

// Export utility functions
window.EventraUtils = {
    showAlert,
    isValidEmail,
    confirmDelete,
    previewImage,
    formatDate
};

