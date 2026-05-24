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

let facilityRefreshTimer = null;

function scheduleFacilityRefresh(delayMs) {
    if (facilityRefreshTimer) {
        clearTimeout(facilityRefreshTimer);
    }

    facilityRefreshTimer = setTimeout(refreshFacilityStatus, Math.max(1000, delayMs || 5000));
}

async function refreshFacilityStatus() {
    try {
        const response = await fetch('/api/facilities/status', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            scheduleFacilityRefresh(5000);
            return;
        }

        const payload = await response.json();
        const nextTransitions = [];

        payload.facilities?.forEach((facility) => {
            const card = document.querySelector(`[data-facility-id="${facility.id}"]`);

            if (!card) {
                return;
            }

            const chip = card.querySelector('.status-chip');
            const message = card.querySelector('[data-status-message]');
            const occupancy = card.querySelector('[data-occupancy]');
            const percentageLabel = card.querySelector('[data-percent-label]');
            const progress = card.querySelector('.progress-circle');
            const assistNote = card.querySelector('[data-assistance-note]');
            const assistButton = card.querySelector('[data-assistance-button]');
            const shouldRequireAssistance = facility.assistance_required === true;

            card.classList.remove('status-card-open', 'status-card-lunch', 'status-card-closed', 'status-card-occupied');
            card.classList.add(
                facility.computed_status === 'closed'
                    ? 'status-card-closed'
                    : facility.computed_status === 'lunch_break'
                        ? 'status-card-lunch'
                        : facility.computed_status === 'open'
                            ? 'status-card-open'
                            : 'status-card-occupied'
            );

            card.dataset.assistanceRequired = shouldRequireAssistance ? '1' : '0';
            chip.className = 'status-chip status-' + facility.computed_status;
            chip.textContent = facility.status_label;
            message.textContent = facility.status_message;
            occupancy.textContent = facility.current_occupancy;
            percentageLabel.textContent = facility.percent + '%';
            progress.setAttribute('data-percent', facility.percent);

            if (shouldRequireAssistance) {
                assistNote.removeAttribute('hidden');
                assistButton.removeAttribute('hidden');
            } else {
                assistNote.setAttribute('hidden', '');
                assistButton.setAttribute('hidden', '');
            }

            if (facility.next_transition_at) {
                nextTransitions.push(new Date(facility.next_transition_at).getTime());
            }
        });

        updateProgressCircles();

        if (nextTransitions.length > 0) {
            const nextRefreshMs = Math.min(...nextTransitions) - Date.now() + 1000;
            scheduleFacilityRefresh(nextRefreshMs);
        } else {
            scheduleFacilityRefresh(5000);
        }
    } catch (error) {
        console.error('Failed to refresh facility status:', error);
        scheduleFacilityRefresh(5000);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    updateActiveSidebar();
    updateProgressCircles();
    refreshFacilityStatus();
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
