document.getElementById('reserve_date').addEventListener('input', function () {
    document.getElementById('display_date').innerText = this.value;
});

document.getElementById('reserve_time').addEventListener('input', function () {
    document.getElementById('display_time').innerText = this.value;
});

document.getElementById('guest_count').addEventListener('input', function () {
    document.getElementById('display_guests').innerText = this.value + ' 人';
});

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelector('.my-review__stars');
    const rating = stars.getAttribute('data-rating');
    stars.style.setProperty('--rating', rating);
});

document.addEventListener('DOMContentLoaded', function () {
    const imgItem = document.querySelector('.my-review__img-item');

    if (imgItem) {
        imgItem.addEventListener('click', function () {
            this.classList.toggle('fullscreen');
        });
    }
});
