// Eventra JavaScript - Modern Interactions

// Navbar mobile toggle (if needed)
document.addEventListener('DOMContentLoaded', function() {
    // Card hover animations
    const cards = document.querySelectorAll('.event-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const required = this.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ff6b6b';
                    valid = false;
                } else {
                    field.style.borderColor = '#667eea';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                showAlert('Please fill all required fields', 'error');
            }
        });
    });
    
    // Card number formatting
    const cardInputs = document.querySelectorAll('input[name=\"card_number\"]');
    cardInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value.slice(0, 19);
        });
    });
});

// Utility function for alerts
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert ${type}`;
    alert.textContent = message;
    document.querySelector('.container')?.prepend(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^=\"#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Loading animation on form submit
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type=\"submit\"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i> Processing...';
        }
    });
});

