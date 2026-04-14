const searchInput = document.getElementById('riderSearch');
const riders      = document.querySelectorAll('.rider-item');
const emptyBlock  = document.getElementById('noResults');

searchInput.addEventListener('input', function () {

    let value = this.value.toLowerCase().trim();

    /* 🔹 Если меньше 2 символов — показать всех */
    if (value.length < 2) {

        riders.forEach(rider => {
            rider.style.display = '';
        });

        emptyBlock.style.display = 'none';
        return;
    }

    /* 🔍 Поиск */
    let visible = 0;

    riders.forEach(rider => {

        let name = rider.querySelector('.rider-name')
            .innerText
            .toLowerCase();

        if (name.includes(value)) {
            rider.style.display = '';
            visible++;
        } else {
            rider.style.display = 'none';
        }

    });

    /* Empty */
    emptyBlock.style.display = visible ? 'none' : 'block';

});