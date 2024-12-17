document.addEventListener('DOMContentLoaded', function () {
    const seatsInRowInput = document.getElementById('seats_in_row');
    const rowsInput = document.getElementById('rows');
    const seatContainer = document.getElementById('seatContainer');
    const driverSeat = document.getElementById('driverSeat');

    // Функция для создания сетки
    function createGrid(rows, seatsInRow) {
        seatContainer.innerHTML = ''; // Очищаем существующие ряды
        for (let r = 1; r <= rows; r++) {
            const newRow = document.createElement('div');
            newRow.classList.add('seat-row');

            for (let i = 1; i <= seatsInRow; i++) {
                const seatDiv = document.createElement('div');
                seatDiv.classList.add('grid'); // Добавляем класс для сетки
                seatDiv.innerText = `Место ${i}`;
                newRow.appendChild(seatDiv);
            }

            seatContainer.appendChild(newRow);
        }
    }

    // Обработчик события ввода количества рядов
    rowsInput.addEventListener('input', function () {
        const rows = parseInt(rowsInput.value) || 0;
        const seatsInRow = parseInt(seatsInRowInput.value) || 0;
        createGrid(rows, seatsInRow);
    });

    // Обработчик события для количества мест в ряду
    seatsInRowInput.addEventListener('input', function () {
        if (rowsInput.value) {
            rowsInput.dispatchEvent(new Event('input'));
        }
    });

    // Перетаскивание водителя
    driverSeat.addEventListener('dragstart', function (e) {
        e.dataTransfer.setData('text/plain', null); // Для Firefox
        const rect = driverSeat.getBoundingClientRect();
        driverSeat.dataset.offsetX = e.clientX - rect.left;
        driverSeat.dataset.offsetY = e.clientY - rect.top;
    });

    document.addEventListener('dragover', function (e) {
        e.preventDefault(); // Необходимо для разрешения сброса
    });

    document.addEventListener('drop', function (e) {
        e.preventDefault();

        // Получаем позицию мыши при сбросе с учетом смещения
        let x = e.clientX - parseFloat(driverSeat.dataset.offsetX);
        let y = e.clientY - parseFloat(driverSeat.dataset.offsetY);

        // Ограничиваем перемещение водителя по контейнеру
        const seatContainerRect = seatContainer.getBoundingClientRect();
        const driverRect = driverSeat.getBoundingClientRect();

        // Ограничивать водителя в пределах контейнера сидений
        if (x < seatContainerRect.left) x = seatContainerRect.left;
        if (x + driverRect.width > seatContainerRect.right) x = seatContainerRect.right - driverRect.width;
        if (y < seatContainerRect.top) y = seatContainerRect.top;
        if (y + driverRect.height > seatContainerRect.bottom) y = seatContainerRect.bottom - driverRect.height;

        // Устанавливаем новое положение водителя
        driverSeat.style.left = `${x - seatContainerRect.left}px`; // Корректирует к координатам контейнера
        driverSeat.style.top = `${y - seatContainerRect.top}px`; // Корректирует к координатам контейнера
    });
});
