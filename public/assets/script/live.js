(() => {

    const REFRESH_INTERVAL = 15;
    let timeLeft = REFRESH_INTERVAL;

    const timerEl = document.getElementById('refresh-timer');
    const filter = document.getElementById('filter-select');

    const eventId = document.getElementById('event-id')?.value;
    const csrftoken = document.getElementById('csrf-token')?.value;

    // ✅ массив открытых строк
    let openedRows = [];

    // ✅ отслеживаем клики
    document.addEventListener('click', function(e){

        const btn = e.target.closest('.toggle-details');
        if(!btn) return;

        const id = btn.dataset.id;
        const row = document.getElementById('details-' + id);

        // если строка открыта — удаляем
        if(row && row.style.display === 'table-row'){
            openedRows = openedRows.filter(item => item != id);
        } else {
            // если закрыта — добавляем
            if (!openedRows.includes(id)) {
                openedRows.push(id);
            }
        }

    });


    function updateLive() {

        const formData = new FormData();

        formData.append('filter', filter ? filter.value : '');
        formData.append('eventId', eventId);
        formData.append('csrftoken', csrftoken);

        fetch('/event-live-upd', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })

            .then(response => response.text())

            .then(html => {

                const container = document.getElementById('live-container');

                if (container) {
                    container.innerHTML = html;
                }

                // ✅ восстановление ВСЕХ открытых строк
                if (openedRows.length) {

                    openedRows.forEach(id => {

                        const row = document.getElementById('details-' + id);
                        const mainRow = document.querySelector('.main-row[data-id="'+id+'"]');
                        const icon = document.querySelector('.toggle-details[data-id="'+id+'"] .toggle-icon');

                        if(row) row.style.display = 'table-row';
                        if(mainRow) mainRow.classList.add('active-row');
                        if(icon) icon.textContent = '–';

                    });

                    // ✅ чистим массив от несуществующих строк
                    openedRows = openedRows.filter(id => document.getElementById('details-' + id));
                }

                timeLeft = REFRESH_INTERVAL;

            })

            .catch(err => console.log(err));

    }

    setInterval(() => {

        timeLeft--;

        if (timerEl) {
            timerEl.textContent = timeLeft;
        }

        if (timeLeft <= 0) {
            updateLive();
        }

    }, 1000);

})();