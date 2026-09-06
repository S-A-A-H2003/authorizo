<script>
    document.querySelectorAll('.banner[id]').forEach((banner) => {
        setTimeout(() => {
            banner.style.transition = 'opacity .3s ease';
            banner.style.opacity = '0';

            setTimeout(() => banner.remove(), 300);
        }, 3000);
    });
</script>
