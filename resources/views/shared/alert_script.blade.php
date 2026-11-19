@if (session('success') || session('error'))
    <div id="floating-alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('floating-alert-container');
            const msg = @json(session('success') ?? session('error'));
            const type = @json(session('success') ? 'success' : 'danger');

            if (container && msg) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} shadow-lg alert-dismissible fade show`;
                const content = document.createElement('div');
                content.className = 'd-flex align-items-center';
                const icon = document.createElement('i');
                icon.className = `bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}-fill me-2 fs-5`;
                const message = document.createElement('div');
                message.textContent = msg;
                const closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.className = 'btn-close';
                closeButton.setAttribute('data-bs-dismiss', 'alert');
                content.append(icon, message, closeButton);
                alertDiv.appendChild(content);
                container.appendChild(alertDiv);
                setTimeout(() => {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 300);
                }, 3000);
            }
        });
    </script>
@endif
