<?php
/**
 * MMCS ADMIN PANEL LAYOUT — FOOTER
 * ================================
 * Closes the .main-content div, loads Bootstrap JS,
 * and includes the sidebar toggle script.
 */
?>
    </div> <!-- End .main-content -->

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar toggle for mobile — toggles .active class on #adminSidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Auto-close sidebar when clicking outside on smaller screens
            document.addEventListener('click', function(event) {
                const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
                if (!isClickInside && sidebar.classList.contains('active') && window.innerWidth < 992) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
