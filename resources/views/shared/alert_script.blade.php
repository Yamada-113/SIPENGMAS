@if (session('success') || session('error'))
    <div id="floating-alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('floating-alert-container');
            const msg = "{{ session('success') ?? session('error') }}";
            const type = "{{ session('success') ? 'success' : 'danger' }}";

            if (container && msg) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} shadow-lg alert-dismissible fade show`;
                alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${type == 'success' ? 'check-circle' : 'exclamation-circle'}-fill me-2 fs-5"></i>
                    <div>${msg}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                container.appendChild(alertDiv);
                setTimeout(() => {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 300);
                }, 3000);
            }
        });
    </script>
@endif
