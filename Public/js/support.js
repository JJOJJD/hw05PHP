const form = document.getElementById('supportForm');
const alertSuccess = document.getElementById('alert-success');
const alertError = document.getElementById('alert-error');
const submitBtn = document.getElementById('submitSupportForm');

form.addEventListener('submit', async function (e) {
    e.preventDefault();

    alertSuccess.style.display = 'none';
    alertError.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    const payload = {
        full_name: document.getElementById('fullName').value.trim(),
        ruc:       document.getElementById('ruc').value.trim(),
        email:     document.getElementById('email').value.trim(),
        category:  document.getElementById('category').value,
        subject:   document.getElementById('subject').value.trim(),
        message:   document.getElementById('message').value.trim(),
        priority:  document.getElementById('priority').value
    };

    try {
        const response = await fetch('../php/support_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Unexpected error');
        }

        alertSuccess.style.display = 'block';
        form.reset();
    } catch (err) {
        alertError.style.display = 'block';
        alertError.textContent = 'Error: ' + err.message;
    }

    submitBtn.disabled = false;
    submitBtn.textContent = 'Send Message';
});
