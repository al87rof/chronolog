document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('searchInput');
    const items = document.querySelectorAll('.event-item');
    const noResults = document.getElementById('noResults');

    input.addEventListener('keyup', function () {

        let value = this.value.toLowerCase().trim();
        let visible = 0;

        items.forEach(item => {

            let name = item.dataset.name || '';
            let desc = item.dataset.desc || '';

            if (name.includes(value) || desc.includes(value)) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }

        });

        if (visible === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }

    });

});