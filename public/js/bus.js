/******/ (() => { // webpackBootstrap
/*!*****************************!*\
  !*** ./resources/js/bus.js ***!
  \*****************************/
document.addEventListener('DOMContentLoaded', function () {
  var seatsInRowInput = document.getElementById('seats_in_row');
  var rowsInput = document.getElementById('rows');
  var seatContainer = document.getElementById('seatContainer');
  var driverSeat = document.getElementById('driverSeat');

  // Функция для создания сетки
  function createGrid(rows, seatsInRow) {
    seatContainer.innerHTML = ''; // Очищаем существующие ряды
    for (var r = 1; r <= rows; r++) {
      var newRow = document.createElement('div');
      newRow.classList.add('seat-row');
      for (var i = 1; i <= seatsInRow; i++) {
        var seatDiv = document.createElement('div');
        seatDiv.classList.add('grid'); // Добавляем класс для сетки
        seatDiv.innerText = "\u041C\u0435\u0441\u0442\u043E ".concat(i);
        newRow.appendChild(seatDiv);
      }
      seatContainer.appendChild(newRow);
    }
  }

  // Обработчик события ввода количества рядов
  rowsInput.addEventListener('input', function () {
    var rows = parseInt(rowsInput.value) || 0;
    var seatsInRow = parseInt(seatsInRowInput.value) || 0;
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
    var rect = driverSeat.getBoundingClientRect();
    driverSeat.dataset.offsetX = e.clientX - rect.left;
    driverSeat.dataset.offsetY = e.clientY - rect.top;
  });
  document.addEventListener('dragover', function (e) {
    e.preventDefault(); // Необходимо для разрешения сброса
  });
  document.addEventListener('drop', function (e) {
    e.preventDefault();

    // Получаем позицию мыши при сбросе с учетом смещения
    var x = e.clientX - parseFloat(driverSeat.dataset.offsetX);
    var y = e.clientY - parseFloat(driverSeat.dataset.offsetY);

    // Ограничиваем перемещение водителя по контейнеру
    var seatContainerRect = seatContainer.getBoundingClientRect();
    var driverRect = driverSeat.getBoundingClientRect();

    // Ограничивать водителя в пределах контейнера сидений
    if (x < seatContainerRect.left) x = seatContainerRect.left;
    if (x + driverRect.width > seatContainerRect.right) x = seatContainerRect.right - driverRect.width;
    if (y < seatContainerRect.top) y = seatContainerRect.top;
    if (y + driverRect.height > seatContainerRect.bottom) y = seatContainerRect.bottom - driverRect.height;

    // Устанавливаем новое положение водителя
    driverSeat.style.left = "".concat(x - seatContainerRect.left, "px"); // Корректирует к координатам контейнера
    driverSeat.style.top = "".concat(y - seatContainerRect.top, "px"); // Корректирует к координатам контейнера
  });
});
/******/ })()
;