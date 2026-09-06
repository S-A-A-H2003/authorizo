@props([
    'rowSelector',
    'fields' => ['name'],
    'roleFilter' => false,
    'itemLabel' => 'items',
])

<script>
    (function () {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const rows = Array.from(document.querySelectorAll('{{ $rowSelector }}'));
        const resultCount = document.getElementById('resultCount');
        const noMatches = document.getElementById('noMatches');
        const fields = @json($fields);
        const itemLabel = @json($itemLabel);
        const useRoleFilter = {{ $roleFilter ? 'true' : 'false' }};

        function applyFilters() {
            const q = searchInput.value.trim().toLowerCase();
            const roleId = useRoleFilter && roleFilter ? roleFilter.value : '';
            let visible = 0;

            rows.forEach((row) => {
                const matchesText = !q || fields.some((field) => (row.dataset[field] || '').includes(q));
                const matchesRole = !roleId || row.dataset.roleId === roleId;
                const show = matchesText && matchesRole;

                row.classList.toggle('is-hidden', !show);

                if (show) visible++;
            });

            resultCount.textContent = rows.length
                ? `Showing ${visible} of ${rows.length} ${itemLabel}`
                : '';

            if (noMatches) {
                noMatches.style.display = (rows.length && visible === 0) ? 'block' : 'none';
            }
        }

        searchInput?.addEventListener('input', applyFilters);
        roleFilter?.addEventListener('change', applyFilters);
        applyFilters();
    })();
</script>
