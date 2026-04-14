const searchInput = document.getElementById('table-search');

let lastFoundRow = null;

searchInput.addEventListener('keyup', function () {

    const value = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.leaderboard-table tbody tr');

    let found = false;

    rows.forEach(row => {

        row.classList.remove('search-highlight');

        if (!found && value !== '') {

            const cells = row.querySelectorAll('td');

            if (cells.length >= 3) {

                const text2 = cells[1].innerText.toLowerCase(); // 2 колонка
                const text3 = cells[2].innerText.toLowerCase(); // 3 колонка

                if (text2.includes(value) || text3.includes(value)) {

                    row.classList.add('search-highlight');
                    found = true;

                    if (lastFoundRow !== row) {

                        row.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        lastFoundRow = row;
                    }
                }
            }
        }

    });

    if (!found) {
        lastFoundRow = null;
    }

});


$(document).on('click', '.toggle-details', function () {

    const id = $(this).data('id');
    const row = $('#details-' + id);
    const icon = $(this).find('.toggle-icon');
    const mainRow = $('tr.main-row[data-id="' + id + '"]');

    if (row.is(':visible')) {

        row.hide();
        icon.text('+');
        mainRow.removeClass('active-row');

    } else {

        row.show();
        icon.text('–');
        mainRow.addClass('active-row');
    }
});