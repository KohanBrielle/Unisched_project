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