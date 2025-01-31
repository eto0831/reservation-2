document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.querySelector('.sort-select');
    const allSelects = document.querySelectorAll('.search-form__item-select');
    const searchInput = document.querySelector('.search-form__item-input');
    let sortSelectClicked = false;

    // ソートセレクタがクリックされたらフラグを立てる
    sortSelect.addEventListener('click', () => {
        sortSelectClicked = true;
    });

    // ドキュメント全体のクリックイベント
    document.addEventListener('click', e => {
        let isClickInsideAnySelect = false;

        // クリックがどれかのセレクタ内かチェック
        allSelects.forEach(select => {
            if (select.contains(e.target)) {
                isClickInsideAnySelect = true;
            }
        });

        // サーチボックスがクリックされた場合もリセットを無視
        if (searchInput.contains(e.target)) {
            isClickInsideAnySelect = true;
        }

        // セレクタ外をクリックした場合のみリセット
        if (!isClickInsideAnySelect && sortSelectClicked) {
            sortSelect.value = ''; // 初期値に戻す
            sortSelect.form.submit(); // フォーム送信
            sortSelectClicked = false; // フラグをリセット
        }
    });

    // 各セレクタの操作時に外部のリセットを無効化
    allSelects.forEach(select => {
        select.addEventListener('change', e => {
            e.stopPropagation();
        });
    });
});
