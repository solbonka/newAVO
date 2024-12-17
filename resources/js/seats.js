const buyButton = document.getElementById('buyButton');

document.querySelectorAll('.seat').forEach(seat => {
    seat.addEventListener('click', function () {
        if (this.classList.contains('occupied')) {
            alert('Это место выбрать нельзя!');
            return;
        }

        this.classList.toggle('selected');

        const selectedSeats = document.querySelectorAll('.seat.selected');
        buyButton.style.display = selectedSeats.length > 0 ? 'block' : 'none';
    });
});

buyButton.addEventListener('click', function () {
    const selectedSeats = [];
    document.querySelectorAll('.seat.selected').forEach(seat => {
        selectedSeats.push(seat.dataset.seat);
    });
    if (selectedSeats.length === 0) {
        alert('Пожалуйста, выберите хотя бы одно место.');
        return;
    }

    console.log('Выбранные места: ', selectedSeats);

    // AJAX запрос можно добавить здесь
});
