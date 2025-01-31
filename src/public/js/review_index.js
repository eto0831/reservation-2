document.addEventListener('DOMContentLoaded', function () {
    // 画像の拡大縮小機能
    const imgItems = document.querySelectorAll('.review__img-item'); // すべての画像を取得

    imgItems.forEach(img => {
        img.addEventListener('click', function () {
            this.classList.toggle('fullscreen'); // クリック時に拡大/縮小を切り替え
        });
    });

    // フルスクリーン時に画像以外をクリックして閉じる
    document.addEventListener('click', function (e) {
        const fullscreenImg = document.querySelector('.review__img-item.fullscreen');
        if (fullscreenImg && !fullscreenImg.contains(e.target)) {
            fullscreenImg.classList.remove('fullscreen');
        }
    });

    // 星評価の設定
    const stars = document.querySelectorAll('.stars');
    stars.forEach(function (star) {
        const rating = star.getAttribute('data-rating');
        star.style.setProperty('--rating', rating);
    });
});
