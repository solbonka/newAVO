/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/schemas/create.js ***!
  \****************************************/
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
document.addEventListener('DOMContentLoaded', function () {
  var seatNumbers = new Map(); // Храним номера мест и их состояние

  window.changeValue = function (id, delta) {
    var input = document.getElementById(id);
    var currentValue = parseInt(String(input.value));
    currentValue = isNaN(currentValue) ? 0 : currentValue; // Проверка на NaN
    input.value = Math.max(1, currentValue + delta); // Ограничение минимального значения
    checkTotalSeats();
  };
  window.generateBus = function () {
    var bus = document.getElementById('bus');
    bus.innerHTML = ''; // Очищаем предыдущую схему

    var rows = parseInt(document.getElementById('rows').value) || 0;
    var seatsPerRow = parseInt(document.getElementById('seatsPerRow').value) || 0;
    var totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;

    // Обновление seatNumbers
    var updatedSeatNumbers = new Map();
    for (var i = 0; i < rows; i++) {
      for (var j = 0; j < seatsPerRow; j++) {
        var seatKey = "".concat(i, "-").concat(j);
        var existingSeat = seatNumbers.get(seatKey);
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
      window.placeSchemas.forEach(function (placeSchema) {
        // Используем row и seat_in_row для создания ключа
        var seatKey = "".concat(placeSchema.row - 1, "-").concat(placeSchema.seat_in_row - 1);

        // Определяем статус: занят или свободен
        var seatData = {
          status: placeSchema.place_id ? 'occupied' : 'free',
          // Проверяем наличие place_id
          number: placeSchema.place_id ? placeSchema.place_id : null // Используем place_id как номер места
        };
        updatedSeatNumbers.set(seatKey, seatData);
      });
    }
    seatNumbers = updatedSeatNumbers;

    // Теперь отрисовываем места
    for (var _i = 0; _i < rows; _i++) {
      var rowDiv = document.createElement('div');
      rowDiv.classList.add('row');
      var _loop = function _loop() {
        var seatKey = "".concat(_i, "-").concat(_j);
        var seatData = seatNumbers.get(seatKey) || {
          status: 'free'
        };
        var seatDiv = document.createElement('div');
        seatDiv.className = "seat ".concat(seatData.status === 'occupied' ? 'taken' : 'empty');
        seatDiv.textContent = seatData.status === 'occupied' ? seatData.number : '';
        seatDiv.onclick = function () {
          handleSeatClick(seatKey, seatData, seatDiv, totalSeats);
        };
        rowDiv.appendChild(seatDiv);
      };
      for (var _j = 0; _j < seatsPerRow; _j++) {
        _loop();
      }
      bus.appendChild(rowDiv);
    }
  };
  window.handleSeatClick = function (seatKey, seatData, seatDiv, totalSeats) {
    var seatSelectionDiv = document.getElementById('seatSelection');
    var availableSeatsDiv = document.getElementById('availableSeats');

    // Инициализация массива доступных мест
    var availableSeats = Array.from({
      length: totalSeats
    }, function (_, i) {
      return i + 1;
    });

    // Обновляем массив доступных мест исходя из занятых мест
    seatNumbers.forEach(function (seat) {
      if (seat.status === 'occupied') {
        availableSeats = availableSeats.filter(function (num) {
          return num !== seat.number;
        });
      }
    });

    // Очищаем контейнер доступных мест
    availableSeatsDiv.innerHTML = '';
    if (seatData.status === 'free') {
      // Создаем кнопки для доступных мест
      availableSeats.forEach(function (number) {
        var button = document.createElement('button');
        button.textContent = "".concat(number);
        button.onclick = function () {
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
      var _seatDiv$getBoundingC = seatDiv.getBoundingClientRect(),
        top = _seatDiv$getBoundingC.top,
        left = _seatDiv$getBoundingC.left;
      var rect = seatDiv.getBoundingClientRect();
      var parent = seatSelectionDiv.parentElement;
      console.log(rect.top, rect.right, rect.bottom, rect.left, rect.width, rect.height);
      seatSelectionDiv.style.display = 'block';
      seatSelectionDiv.style.position = 'absolute'; // Убедитесь, что позиционирование абсолютное
      if (parent.className === "modal-body") {
        seatSelectionDiv.style.top = top - seatSelectionDiv.offsetHeight + 'px'; // Сместите вверх
        seatSelectionDiv.style.left = '50%'; // Позиционируйте по левому краю
        seatSelectionDiv.style.transform = 'translateX(-50%)';
      } else {
        seatSelectionDiv.style.top = top - seatSelectionDiv.offsetHeight + 'px'; // Сместите вверх
        seatSelectionDiv.style.left = left + 'px'; // Позиционируйте по левому краю
      }
    } else {
      var currentSeat = seatNumbers.get(seatKey);
      var confirmClear = confirm("\u0421\u0438\u0434\u0435\u043D\u044C\u0435 \u0437\u0430\u043D\u044F\u0442\u043E \u043D\u043E\u043C\u0435\u0440\u043E\u043C ".concat(currentSeat.number, ". \u0425\u043E\u0442\u0438\u0442\u0435 \u043E\u0447\u0438\u0441\u0442\u0438\u0442\u044C \u044D\u0442\u043E \u043C\u0435\u0441\u0442\u043E?"));
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
    document.getElementById('closeSeatSelection').onclick = function () {
      seatSelectionDiv.style.display = 'none'; // Закрываем выбор
    };
  };
  window.checkTotalSeats = function () {
    var rows = parseInt(document.getElementById('rows').value) || 0;
    var seatsPerRow = parseInt(document.getElementById('seatsPerRow').value) || 0;
    var maxSeats = rows * seatsPerRow;
    var totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;
    if (totalSeats > maxSeats) {
      alert("\u041E\u0431\u0449\u0435\u0435 \u043A\u043E\u043B\u0438\u0447\u0435\u0441\u0442\u0432\u043E \u043C\u0435\u0441\u0442 \u043D\u0435 \u043C\u043E\u0436\u0435\u0442 \u043F\u0440\u0435\u0432\u044B\u0448\u0430\u0442\u044C ".concat(maxSeats, "."));
      document.getElementById('totalSeats').value = maxSeats; // Сбрасываем значение
      totalSeats = maxSeats; // Обновляем переменную
    }
    clearExceedingSeats(totalSeats);
    generateBus(); // Генерируем заново автобус с новым количеством мест
  };
  window.clearExceedingSeats = function (totalSeats) {
    seatNumbers.forEach(function (seatDiv, seatNum) {
      if (seatDiv.number > totalSeats) {
        seatNumbers["delete"](seatNum); // Удаляем номер из набора
      }
    });
  };
  window.saveSeats = function () {
    var totalSeats = parseInt(document.getElementById('totalSeats').value) || 0;
    var seatCount = seatNumbers.size;
    var name = document.getElementById('schemaName').value;
    if (!name) {
      alert("\u041F\u043E\u0436\u0430\u043B\u0443\u0439\u0441\u0442\u0430, \u0437\u0430\u0434\u0430\u0439\u0442\u0435 \u043D\u0430\u0437\u0432\u0430\u043D\u0438\u0435 \u0441\u0445\u0435\u043C\u044B.");
      return;
    }
    if (seatCount < totalSeats) {
      alert("\u041F\u043E\u0436\u0430\u043B\u0443\u0439\u0441\u0442\u0430, \u0437\u0430\u0434\u0430\u0439\u0442\u0435 \u043D\u043E\u043C\u0435\u0440\u0430 \u0434\u043B\u044F \u0432\u0441\u0435\u0445 \u043C\u0435\u0441\u0442. \u0412\u044B \u0437\u0430\u0434\u0430\u043B\u0438 \u0442\u043E\u043B\u044C\u043A\u043E ".concat(seatCount, " \u0438\u0437 ").concat(totalSeats, "."));
      return; // Завершаем выполнение, если мест недостаточно
    }
    var seatData = {};
    seatNumbers.forEach(function (seat, key) {
      var _key$split = key.split('-'),
        _key$split2 = _slicedToArray(_key$split, 2),
        row = _key$split2[0],
        seat_in_row = _key$split2[1];
      var rowIndex = parseInt(row) + 1;
      var seatIndex = parseInt(seat_in_row) + 1;
      if (!seatData[rowIndex]) {
        seatData[rowIndex] = []; // Создаем массив для этой строки, если его еще нет
      }
      seatData[rowIndex].push({
        seat_in_row: seatIndex,
        status: seat.status,
        number: seat.number // сюда добавляйте при необходимости
      });
    });
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var fetchUrl; // Убедитесь, что переменная объявлена заранее
    var method;
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
    }).then(function (response) {
      console.log(response);
      if (!response.ok) {
        throw new Error('Сеть не доступна или сервер вернул ошибку');
      }
      return response.json(); // Парсим ответ как JSON
    }).then(function (data) {
      if (data.success) {
        alert(data.message);
        var schemaSelect = document.getElementById('schema_id');
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
    })["catch"](function (error) {
      console.error('Ошибка:', error);
      alert('Ошибка при сохранении данных!');
    });
  };

  // Вызов функции для инициализации при загрузке страницы
  window.onload = function () {
    generateBus();
  };
  window.fetchSchemaDetails = function (schemaId) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (!schemaId) {
      document.getElementById('schemaDetails').style.display = 'none';
      return;
    }
    fetch("/schemas/".concat(schemaId), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    }).then(function (response) {
      return response.json();
    }).then(function (data) {
      // Обновление информации о схеме
      document.getElementById('schemaName').innerText = data.schema.name;
      document.getElementById('schemaRows').innerText = data.schema.rows;
      document.getElementById('schemaSeatsInRow').innerText = data.schema.seat_in_rows;
      document.getElementById('schemaCapacity').innerText = data.schema.capacity;

      // Отрисовка мест
      var busSeating = document.getElementById('busSeating');
      busSeating.innerHTML = ''; // Очищаем старые места
      busSeating.style.gridTemplateColumns = "repeat(".concat(data.schema.seat_in_rows, ", 1fr)"); // Устанавливаем количество столбцов

      // Группируем места по рядам и столбцам
      var rows = data.schema.rows;
      var seatsInRow = data.schema.seat_in_rows;
      var _loop2 = function _loop2(row) {
        var _loop3 = function _loop3(seat) {
          var placeSchema = data.placeSchemas.find(function (p) {
            return p.row === row && p.seat_in_row === seat;
          });
          var seatDiv = document.createElement('div');
          if (placeSchema.place_id) {
            seatDiv.className = 'seat appointed';
            seatDiv.innerText = placeSchema.place_id;
          } else {
            seatDiv.className = 'seat empty';
          }
          busSeating.appendChild(seatDiv); // Добавляем место в разметку
        };
        for (var seat = 1; seat <= seatsInRow; seat++) {
          _loop3(seat);
        }
      };
      for (var row = 1; row <= rows; row++) {
        _loop2(row);
      }
      document.getElementById('schemaDetails').style.display = 'block';
    })["catch"](function (error) {
      console.error(error);
      alert('Ошибка загрузки данных. Пожалуйста, попробуйте позже.');
    });
  };
});
/******/ })()
;