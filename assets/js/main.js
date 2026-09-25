/* MMCS Premium Website - Client JS Orchestrator */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Dynamic Scroll Navbar Glassmorphism
    const navbar = document.querySelector('.navbar');
    
    function checkScroll() {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
            navbar.style.backgroundColor = 'rgba(15, 23, 42, 0.95)';
            navbar.style.backdropFilter = 'blur(8px)';
            navbar.style.paddingTop = '12px';
            navbar.style.paddingBottom = '12px';
        } else {
            navbar.style.boxShadow = 'none';
            navbar.style.backgroundColor = '#0f172a';
            navbar.style.backdropFilter = 'none';
            navbar.style.paddingTop = '16px';
            navbar.style.paddingBottom = '16px';
        }
    }
    
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Initial run on load

    // 2. Client-side Portfolio Grid Filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card-item');

    if (filterButtons.length > 0 && projectCards.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                // Remove active class from all buttons
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const selectedSector = this.getAttribute('data-filter');

                // Filter cards with smooth scale animations
                projectCards.forEach(card => {
                    const cardSector = card.getAttribute('data-sector');
                    
                    if (selectedSector === 'all' || selectedSector === cardSector) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    // 3. Form Validation Polish (Bootstrap logic)
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
