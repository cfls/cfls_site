<!-- Toast + cambio de estado por AJAX (formularios con data-ajax-status) -->
<div id="ajax-toast" class="hidden fixed bottom-6 right-6 z-[60] px-4 py-3 rounded-lg shadow-lg text-sm font-medium text-white transition-opacity"></div>

<script>
    function showToast(message, ok = true) {
        const toast = document.getElementById('ajax-toast');
        if (!toast) return;

        toast.textContent = message;
        toast.classList.remove('hidden', 'bg-green-600', 'bg-red-600');
        toast.classList.add(ok ? 'bg-green-600' : 'bg-red-600');

        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // El botón muestra la acción: status 1 => "Désactiver" (rojo), 0 => "Activer" (verde)
    function renderStatusButton(form, status) {
        const button = form.querySelector('button[type="submit"]');
        if (!button) return;

        form.dataset.status = status;
        button.textContent = status ? 'Désactiver' : 'Activer';
        button.title = status ? 'Désactiver' : 'Activer';
        button.classList.remove('text-green-600', 'hover:text-green-900', 'text-red-600', 'hover:text-red-900');
        button.classList.add(...(status
            ? ['text-red-600', 'hover:text-red-900']
            : ['text-green-600', 'hover:text-green-900']));
    }

    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form[data-ajax-status]');
        if (!form) return;

        e.preventDefault();

        const current = form.dataset.status === '1';
        if (!confirm(`${current ? 'Désactiver' : 'Activer'} cette question ?`)) return;

        const button = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);
        button.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token')
            }
        })
            .then(response => {
                if (!response.ok) throw new Error(response.status);
                return response.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message);
                renderStatusButton(form, data.status ? 1 : 0);
                showToast(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue lors du changement de statut', false);
            })
            .finally(() => {
                button.disabled = false;
            });
    });
</script>
