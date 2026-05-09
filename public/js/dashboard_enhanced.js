document.addEventListener('DOMContentLoaded', () => {
    // 1. Get the current page filename
    const path = window.location.pathname;
    const currentPage = path.split("/").pop();

    // 2. Select all links in the sidebar
    const navLinks = document.querySelectorAll('nav ul li a');

    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');

        // FORCE HIGHLIGHT: If at root or index.html, highlight Facility Overview
        if (currentPage === linkPage || (currentPage === "" && linkPage === "index.html") || (currentPage === "dashboard" && linkPage === "index.html")) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }
    });

    // 2. Circular Progress Animation[cite: 3]
    const circles = document.querySelectorAll('.progress-circle');
    circles.forEach(circle => {
        const percent = circle.getAttribute('data-percent');
        const circlePath = circle.querySelector('circle:last-child');
        const circumference = 2 * Math.PI * 30;

        circlePath.style.strokeDasharray = circumference;
        const offset = circumference - (percent / 100) * circumference;
        circlePath.style.strokeDashoffset = offset;
    });
});

// Function to cancel a reservation
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
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || 'Failed to cancel reservation');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while cancelling the reservation.');
    });
}

// Function to return equipment
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
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    const data = JSON.parse(text);
                    throw new Error(data.error || 'Failed to return equipment');
                } catch (e) {
                    throw new Error('Server error: ' + response.status);
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while returning the equipment.');
    });
}