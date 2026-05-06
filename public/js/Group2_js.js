window.onload = () => {
    const unisPart = document.querySelector('.logo-highlight');
    setTimeout(() => {
        unisPart.style.transition = "all 0.8s ease-in-out";
    }, 500);
};

// Whiten UNIS kapag magtype
const inputs = document.querySelectorAll('input');
const brand = document.querySelector('.logo-highlight');

inputs.forEach(input => {
    input.addEventListener('focus', () => {
        brand.style.color = "#ffffff";
    });

    input.addEventListener('blur', () => {
        brand.style.color = "#d05de7";
        brand.style.transform = "scale(1.0)";
    });
});


