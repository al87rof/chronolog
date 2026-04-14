let highlightEnabled = false;
let classIndex = 7; // начинаем после уже занятых (1–6)

// алиасы
const aliases = {
    'XC-1': 'XC-1',
    'CX-1': 'XC-1',
    'Pro': 'XC-1',
    'MX-1': 'XC-1',
    'Hard (ПРО)': 'XC-1',


    'XC-2': 'XC-2',
    'CX-2': 'XC-2',
    'Open': 'XC-2',
    'MX-2': 'XC-2',
    'Medium (Опен)': 'XC-2',

    'Аматор': 'Аматор',
    'Amateur': 'Аматор',
    'Light (Аматор)': 'Аматор',

    'Free': 'Free',
    'NoLicense': 'Free',
    'CX-F': 'Free',

    'Ветеран': 'Ветеран',
    'Veteran': 'Ветеран',

    'XC-E': 'XC-E',
    'CX-E': 'XC-E',
};

// базовые классы
const classMap = {
    'XC-1': 1,
    'XC-2': 2,
    'Аматор': 3,
    'Free': 4,
    'Ветеран':5,
    'CX-E':6
};

$('#toggle-highlight').on('click', function () {
    highlightEnabled = !highlightEnabled;

    document.querySelectorAll('.main-row').forEach(row => {
        let className = row.children[3].textContent.trim();

        // нормализация
        className = aliases[className] || className;

        // 👉 если класса нет — добавляем
        if (!classMap[className]) {
            classMap[className] = classIndex;
            classIndex = classIndex < 12 ? classIndex + 1 : 5; // по кругу (5–12)
        }

        // чистим старые
        row.classList.remove(
            ...Array.from({length: 12}, (_, i) => 'class-' + (i + 1))
        );

        if (highlightEnabled) {
            row.classList.add('class-' + classMap[className]);
        }
    });

    $(this).toggleClass('btn-outline-secondary btn-success');
});

$('#filter-select').on('change', function () {
    const val = $(this).val();
    const base = window.location.href.split('?')[0];
    window.location.href = base + '?filter=' + val;
});