<!-- Bootstrap JS -->
<script src="assets/js/libs/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="assets/js/libs/jquery-3.7.1.min.js"></script>

<script src="assets/js/libs/sweetalert2.all.min.js"></script>
<script src="assets/js/libs/chart.js"></script>
<script src="assets/js/libs/dropzone.min.js"></script>

<!-- Charts Script (dummy data) -->
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get current page path
        const currentPath = window.location.pathname;
        const currentPage = currentPath.split('/').pop() || 'index.php';
        
        // Select all menu links
        const menuLinks = document.querySelectorAll('.sidebar a');
        
        // Function to update active state
        function updateActiveMenu() {
            menuLinks.forEach(link => {
                // Remove active class and reset SVG color
                link.classList.remove('active');
                const svgPaths = link.querySelectorAll('svg path');
                svgPaths.forEach(path => {
                    path.setAttribute('stroke', '#4B5563');
                });
                
                // Get link's target page
                const linkPath = link.getAttribute('href');
                const linkPage = linkPath.split('/').pop() || 'index.php';
                
                // Check if current page matches link's page
                if (currentPage === linkPage || 
                    (currentPage === '' && linkPage === 'index.php') ||
                    (currentPage.includes('booking') && linkPage === 'booking.php')) {
                    
                    // Add active class and update SVG color
                    link.classList.add('active');
                    svgPaths.forEach(path => {
                        path.setAttribute('stroke', '#2563EB');
                    });
                }
            });
        }
        
        // Initialize active menu
        updateActiveMenu();
        
        // Add click event listeners to update active state
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Prevent default if it's not a real navigation
                if (link.getAttribute('href') === '#') {
                    e.preventDefault();
                }
                
                // Update active state
                updateActiveMenu();
                this.classList.add('active');
                
                // Update SVG color for clicked link
                const svgPaths = this.querySelectorAll('svg path');
                svgPaths.forEach(path => {
                    path.setAttribute('stroke', '#2563EB');
                });
                
                // Close sidebar on mobile after click
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                }
            });
        });
        
        // Mobile sidebar toggle
        const sidebarToggle = document.querySelector('[data-bs-toggle="collapse"]');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('show');
            });
        }
    });

    // Function to show toast notification
    function showToast(title, message, type = 'info') {
        // Remove any existing toasts first
        $('.toast-container').remove();
        
        // Create toast container if it doesn't exist
        if ($('.toast-container').length === 0) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');
        }
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        $('.toast-container').append(toastHtml);
        
        // Add appropriate styling based on type
        const toastElement = $('#' + toastId);
        if (type === 'success') {
            toastElement.find('.toast-header').addClass('bg-success text-white');
        } else if (type === 'error') {
            toastElement.find('.toast-header').addClass('bg-danger text-white');
        } else if (type === 'warning') {
            toastElement.find('.toast-header').addClass('bg-warning text-dark');
        } else {
            toastElement.find('.toast-header').addClass('bg-info text-white');
        }
        
        // Initialize and show the toast
        const toast = new bootstrap.Toast(toastElement[0], {
            autohide: true,
            delay: 5000
        });
        toast.show();
        
        // Remove toast from DOM after it's hidden
        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
</script>