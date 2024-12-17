document.addEventListener('DOMContentLoaded', function() {
let seatNumbers = new Map(); // Храним номера мест и их состояние

    window.changeValue = function(id, delta) {
        const input = document.getElementById(id);
        let currentValue = parseInt(String(input.value));
        currentValue = isNaN(currentValue) ? 0 : currentValue; // Проверка на NaN
        input.value = Math.max(1, currentValue + delta); // Ограничение минимального значения
        checkTotalSeats();
    }

    window.generateBus = function() {
        const bus = document.getElementById('bus');
        bus.innerHTML = ''; // Очищаем предыдущую схему

        const rows = parseInt(document.getElementById('rows').value) || 0;
        const seatsPerRow = parseInt(document.getElementById('seatsPerRow').value) || 0;
        const totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;

        // Обновление seatNumbers
        const updatedSeatNumbers = new Map();

        for (let i = 0; i < rows; i++) {
            for (let j = 0; j < seatsPerRow; j++) {
                const seatKey = `${i}-${j}`;
                const existingSeat = seatNumbers.get(seatKey);
                if (existingSeat) {
                    // Проверяем, что место находится в пределах новой конфигурации
                    if (i < rows && j < seatsPerRow) {
                        updatedSeatNumbers.set(seatKey, existingSeat);
                    }
                }
            }
        }
        console.log(window.placeSchemas);
        if (window.placeSchemas) {
            // Заменяем старые значения на обновленные
            window.placeSchemas.forEach(placeSchema => {
                // Используем row и seat_in_row для создания ключа
                const seatKey = `${placeSchema.row - 1}-${placeSchema.seat_in_row - 1}`;

                // Определяем статус: занят или свободен
                const seatData = {
                    status: placeSchema.place_id ? 'occupied' : 'free', // Проверяем наличие place_id
                    number: placeSchema.place_id ? placeSchema.place_id : null // Используем place_id как номер места
                };

                updatedSeatNumbers.set(seatKey, seatData);
            });
        }

        seatNumbers = updatedSeatNumbers;

        // Теперь отрисовываем места
        for (let i = 0; i < rows; i++) {
            const rowDiv = document.createElement('div');
            rowDiv.classList.add('row');

            for (let j = 0; j < seatsPerRow; j++) {
                const seatKey = `${i}-${j}`;
                const seatData = seatNumbers.get(seatKey) || { status: 'free' };

                const seatDiv = document.createElement('div');
                seatDiv.className = `seat ${seatData.status === 'occupied' ? 'taken' : 'empty'}`;
                seatDiv.textContent = seatData.status === 'occupied' ? seatData.number : '';

                seatDiv.onclick = function() {
                    handleSeatClick(seatKey, seatData, seatDiv, totalSeats);
                };

                rowDiv.appendChild(seatDiv);
            }
            bus.appendChild(rowDiv);
        }
    };

    window.handleSeatClick = function(seatKey, seatData, seatDiv, totalSeats) {
        const seatSelectionDiv = document.getElementById('seatSelection');
        const availableSeatsDiv = document.getElementById('availableSeats');

        // Инициализация массива доступных мест
        let availableSeats = Array.from({ length: totalSeats }, (_, i) => i + 1);

        // Обновляем массив доступных мест исходя из занятых мест
        seatNumbers.forEach(seat => {
            if (seat.status === 'occupied') {
                availableSeats = availableSeats.filter(num => num !== seat.number);
            }
        });

        // Очищаем контейнер доступных мест
        availableSeatsDiv.innerHTML = '';

        if (seatData.status === 'free') {
            // Создаем кнопки для доступных мест
            availableSeats.forEach(number => {
                const button = document.createElement('button');
                button.textContent = `${number}`;
                button.onclick = function() {
                    // Обновляем состояние
                    seatData.status = 'occupied';
                    seatData.number = number;
                    seatNumbers.set(seatKey, seatData);
                    seatDiv.textContent = number; // Устанавливаем текст
                    seatDiv.classList.remove('empty'); // Убираем класс пустого места
                    seatDiv.classList.add('taken'); // Обозначаем, что место занято
                    seatSelectionDiv.style.display = 'none'; // Скрываем выбор
                };
                availableSeatsDiv.appendChild(button);
            });

            // Позиционируем контейнер над местом
            const { top, left } = seatDiv.getBoundingClientRect();
            const rect = seatDiv.getBoundingClientRect();
            let parent = seatSelectionDiv.parentElement;
            console.log(rect.top, rect.right, rect.bottom, rect.left, rect.width, rect.height);
            seatSelectionDiv.style.display = 'block';
            seatSelectionDiv.style.position = 'absolute'; // Убедитесь, что позиционирование абсолютное
            if (parent.className === "modal-body")
            {
                seatSelectionDiv.style.top = (top - seatSelectionDiv.offsetHeight) + 'px'; // Сместите вверх
                seatSelectionDiv.style.left = '50%'; // Позиционируйте по левому краю
                seatSelectionDiv.style.transform = 'translateX(-50%)';
            } else {
                seatSelectionDiv.style.top = (top - seatSelectionDiv.offsetHeight) + 'px'; // Сместите вверх
                seatSelectionDiv.style.left = left + 'px'; // Позиционируйте по левому краю
            }
        } else {
            const currentSeat = seatNumbers.get(seatKey);
            const confirmClear = confirm(`Сиденье занято номером ${currentSeat.number}. Хотите очистить это место?`);
            if (confirmClear) {
                seatData.status = 'free'; // Сбрасываем статус
                delete seatData.number;
                seatNumbers.set(seatKey, seatData); // Сохраняем обновленное состояние
                seatDiv.textContent = ''; // Очищаем текст
                seatDiv.classList.add('empty'); // Возвращаем класс пустого места
                seatDiv.classList.remove('taken'); // Убираем обозначение занятости

                // Добавляем номер обратно в список доступных мест
                availableSeats.push(currentSeat.number);
            }
        }
        document.getElementById('closeSeatSelection').onclick = function() {
            seatSelectionDiv.style.display = 'none'; // Закрываем выбор
        }
    };


    window.checkTotalSeats = function() {
        const rows = parseInt(document.getElementById('rows').value) || 0;
        const seatsPerRow = parseInt(document.getElementById('seatsPerRow').value) || 0;
        const maxSeats = rows * seatsPerRow;
        let totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;

        if (totalSeats > maxSeats) {
            alert(`Общее количество мест не может превышать ${maxSeats}.`);
            document.getElementById('totalSeats').value = maxSeats; // Сбрасываем значение
            totalSeats = maxSeats; // Обновляем переменную
        }
        clearExceedingSeats(totalSeats);
        generateBus(); // Генерируем заново автобус с новым количеством мест
    }

    window.clearExceedingSeats = function (totalSeats) {
        seatNumbers.forEach(function (seatDiv, seatNum) {
            if (seatDiv.number > totalSeats) {
                seatNumbers["delete"](seatNum); // Удаляем номер из набора
            }
        });
    };

    window.saveSeats = function() {
        const totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;
        const seatCount = seatNumbers.size;
        const name = document.getElementById('schemaName').value;
        if (!name) {
            alert(`Пожалуйста, задайте название схемы.`);
            return;
        }

        if (seatCount < totalSeats) {
            alert(`Пожалуйста, задайте номера для всех мест. Вы задали только ${seatCount} из ${totalSeats}.`);
            return; // Завершаем выполнение, если мест недостаточно
        }
        const seatData = {};

        seatNumbers.forEach((seat, key) =>  {
            const [row, seat_in_row] = key.split('-');
            const rowIndex = parseInt(row) + 1;
            const seatIndex = parseInt(seat_in_row) + 1;

            if (!seatData[rowIndex]) {
                seatData[rowIndex] = []; // Создаем массив для этой строки, если его еще нет
            }

            seatData[rowIndex].push({
                seat_in_row: seatIndex,
                status: seat.status,
                number: seat.number // сюда добавляйте при необходимости
            });
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let fetchUrl; // Убедитесь, что переменная объявлена заранее
        let method;

        if (typeof schemaId !== 'undefined') {
            fetchUrl = '/schemas/'.concat(schemaId);
            method = 'PUT';
        } else {
            fetchUrl = '/schemas';
            method = 'POST';
        }
        console.log(seatData);
        fetch(fetchUrl, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                seats: seatData,
                rows: parseInt(document.getElementById('rows').value) || 0,
                seatsPerRow: parseInt(document.getElementById('seatsPerRow').value) || 0,
                capacity: parseInt(document.getElementById('totalSeats').value) || 0,
                name: document.getElementById('schemaName').value || ""
            })
        })
            .then((response) => {
                console.log(response);
                if (!response.ok) {
                    throw new Error('Сеть не доступна или сервер вернул ошибку');
                }
                return response.json(); // Парсим ответ как JSON
            })
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    const schemaSelect = document.getElementById('schema_id');
                    console.log(schemaSelect);
                    if (schemaSelect) {
                        var newOption = document.createElement('option');
                        newOption.value = data.schemaId; // Предполагается, что ID схемы возвращается с сервера
                        newOption.text = data.schemaName; // Имя схемы
                        schemaSelect.add(newOption); // Добавляем опцию в select

                        schemaSelect.value = data.schemaId; // Устанавливаем новую схему выбранной

                        document.getElementById('createSchemaModal').style.display = 'none';
                        document.body.classList.remove('modal-open');
                        document.querySelector('.modal-backdrop').remove(); // Удаляем задний фон
                        fetchSchemaDetails(data.schemaId);
                    }
                } else {
                    alert('Произошла ошибка при сохранении данных.');
                }
                console.log(data);
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка при сохранении данных!');
            });
    };

    // Вызов функции для инициализации при загрузке страницы
    window.onload = function() {
        generateBus();
    };

    window.fetchSchemaDetails = function(schemaId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!schemaId) {
            document.getElementById('schemaDetails').style.display = 'none';
            return;
        }

        fetch(`/schemas/${schemaId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
            .then(response => response.json())
            .then(data => {
                // Обновление информации о схеме
                document.getElementById('schemaName').innerText = data.schema.name;
                document.getElementById('schemaRows').innerText = data.schema.rows;
                document.getElementById('schemaSeatsInRow').innerText = data.schema.seat_in_rows;
                document.getElementById('schemaCapacity').innerText = data.schema.capacity;

                // Отрисовка мест
                const busSeating = document.getElementById('busSeating');
                busSeating.innerHTML = ''; // Очищаем старые места
                busSeating.style.gridTemplateColumns = `repeat(${data.schema.seat_in_rows}, 1fr)`; // Устанавливаем количество столбцов

                // Группируем места по рядам и столбцам
                const rows = data.schema.rows;
                const seatsInRow = data.schema.seat_in_rows;

                for (let row = 1; row <= rows; row++) {
                    for (let seat = 1; seat <= seatsInRow; seat++) {
                        const placeSchema = data.placeSchemas.find(p => p.row === row && p.seat_in_row === seat);
                        const seatDiv = document.createElement('div');

                        if (placeSchema.place_id) {
                            seatDiv.className = 'seat appointed';
                            seatDiv.innerText = placeSchema.place_id;
                        } else {
                            seatDiv.className = 'seat empty';
                        }

                        busSeating.appendChild(seatDiv); // Добавляем место в разметку
                    }
                }

                document.getElementById('schemaDetails').style.display = 'block';
            })
            .catch(error => {
                console.error(error);
                alert('Ошибка загрузки данных. Пожалуйста, попробуйте позже.');
            });
    }
});
