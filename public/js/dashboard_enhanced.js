function updateActiveSidebar() {
    const navLinks = Array.from(document.querySelectorAll('nav ul li a'));
    const currentPath = window.location.pathname;

    navLinks.forEach((link) => {
        const href = new URL(link.href, window.location.origin).pathname;
        const parent = link.parentElement;

        if (href === currentPath) {
            parent.classList.add('active');
        } else {
            parent.classList.remove('active');
        }
    });
}

function setAssistanceVisibility(card, isRequired) {
    const note = card.querySelector('[data-assistance-note]');
    const button = card.querySelector('[data-assistance-button]');

    if (!note || !button) {
        return;
    }

    note.hidden = !isRequired;
    button.hidden = !isRequired;
}

function openAssistanceModal(facilityId, facilityName) {
    const modal = document.getElementById('assistanceModal');
    const facilityInput = document.getElementById('assistanceFacilityId');
    const nameElement = document.getElementById('assistanceFacilityName');
    const textarea = document.getElementById('assistanceMessage');
    const feedback = document.getElementById('assistanceFeedback');

    if (!modal || !facilityInput || !nameElement || !textarea || !feedback) {
        return;
    }

    facilityInput.value = facilityId;
    nameElement.textContent = facilityName;
    textarea.value = '';
    feedback.textContent = '';
    modal.hidden = false;
    textarea.focus();
}

function closeAssistanceModal() {
    const modal = document.getElementById('assistanceModal');

    if (!modal) {
        return;
    }

    modal.hidden = true;
}

async function submitAssistance(event) {
    event.preventDefault();

    const form = event.target;
    const facilityId = document.getElementById('assistanceFacilityId')?.value;
    const message = document.getElementById('assistanceMessage')?.value.trim();
    const feedback = document.getElementById('assistanceFeedback');
    const submitButton = form.querySelector('button[type="submit"]');

    if (!facilityId || !message || !feedback) {
        return;
    }

    if (!message) {
        feedback.textContent = 'Please describe what you need help with.';
        return;
    }

    if (submitButton) {
        submitButton.disabled = true;
    }

    try {
        const response = await fetch('/facility/assistance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                facility_id: facilityId,
                message
            })
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.error || data.message || 'Unable to submit assistance request');
        }

        feedback.textContent = data.message || 'Assistance request submitted successfully';

        window.setTimeout(() => {
            closeAssistanceModal();
        }, 1200);
    } catch (error) {
        feedback.textContent = error.message || 'Unable to submit assistance request';

        if (submitButton) {
            submitButton.disabled = false;
        }
    }
}

function updateProgressCircles() {
    const circles = document.querySelectorAll('.progress-circle');

    circles.forEach((circle) => {
        const percent = Number(circle.getAttribute('data-percent') || 0);
        const circlePath = circle.querySelector('circle:last-child');
        const circumference = 2 * Math.PI * 30;

        circlePath.style.strokeDasharray = circumference;
        const offset = circumference - (percent / 100) * circumference;
        circlePath.style.strokeDashoffset = offset;
    });
}

async function refreshFacilityStatus() {
    const response = await fetch('/api/facilities/status', {
        headers: { 'Accept': 'application/json' }
    });

    if (!response.ok) {
        return;
    }

    const payload = await response.json();

    payload.facilities?.forEach((facility) => {
        const card = document.querySelector(`.status-card[data-facility-id="${facility.id}"]`);

        if (!card) {
            return;
        }

        const chip = card.querySelector('.status-chip');
        const message = card.querySelector('[data-status-message]');
        const occupancy = card.querySelector('[data-occupancy]');
        const percentageLabel = card.querySelector('[data-percent-label]');
        const progress = card.querySelector('.progress-circle');

        if (!chip || !message || !occupancy || !percentageLabel || !progress) {
            return;
        }

        card.classList.remove('status-card-open', 'status-card-lunch', 'status-card-closed', 'status-card-occupied');
        card.classList.add(
            facility.computed_status === 'closed'
                ? 'status-card-closed'
                : facility.computed_status === 'lunch_break'
                    ? 'status-card-lunch'
                    : ['reserved', 'in_use'].includes(facility.computed_status)
                        ? 'status-card-occupied'
                        : 'status-card-open'
        );

        chip.className = 'status-chip status-' + facility.computed_status;
        chip.textContent = facility.status_label;
        message.textContent = facility.status_message;
        occupancy.textContent = facility.current_occupancy;
        percentageLabel.textContent = facility.percent + '%';
        progress.setAttribute('data-percent', facility.percent);
        setAssistanceVisibility(card, Boolean(facility.assistance_required));
        updateProgressCircles();
    });
}

window.addEventListener('DOMContentLoaded', () => {
    updateActiveSidebar();
    updateProgressCircles();
    refreshFacilityStatus();
    setInterval(refreshFacilityStatus, 30000);

    const assistanceForm = document.getElementById('assistanceForm');

    if (assistanceForm) {
        assistanceForm.addEventListener('submit', submitAssistance);
    }
});

function cancelReservation(reservationId) {
    if (!confirm('Are you sure you want to cancel this reservation?')) {
        return;
    }

    fetch(`/reservations/${reservationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then((response) => {
            if (!response.ok) {
                return response.json().then((data) => {
                    throw new Error(data.error || 'Failed to cancel reservation');
                });
            }

            return response.json();
        })
        .then((data) => {
            if (data.message) {
                alert(data.message);
                location.reload();
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while cancelling the reservation.');
        });
}

function returnEquipment(borrowingId) {
    if (!confirm('Are you sure you want to return this equipment?')) {
        return;
    }

    fetch(`/borrowings/${borrowingId}/request-return`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then((response) => {
            if (!response.ok) {
                return response.text().then((text) => {
                    try {
                        const data = JSON.parse(text);
                        throw new Error(data.error || 'Failed to return equipment');
                    } catch (error) {
                        throw new Error('Server error: ' + response.status);
                    }
                });
            }

            return response.json();
        })
        .then((data) => {
            if (data.message) {
                alert(data.message);
                location.reload();
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while returning the equipment.');
        });
}
