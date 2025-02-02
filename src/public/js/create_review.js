document.addEventListener('DOMContentLoaded', function () {
    // 画像アップロード関連
    const inputImage = document.getElementById('image');
    const dragDropArea = document.getElementById('drag-drop-area');
    const preview = document.getElementById('preview');

    inputImage.addEventListener('change', () => {
        handleFiles(inputImage.files);
    });

    dragDropArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        dragDropArea.classList.add('dragover');
    });

    dragDropArea.addEventListener('dragleave', function (e) {
        e.preventDefault();
        dragDropArea.classList.remove('dragover');
    });

    dragDropArea.addEventListener('drop', function (e) {
        e.preventDefault();
        dragDropArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
        inputImage.files = files;
    });

    dragDropArea.addEventListener('click', function () {
        inputImage.click();
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                alert('画像ファイルを選択してください。');
                inputImage.value = ''; // 入力をリセット
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    }

    // 星の評価選択
    const stars = document.querySelectorAll('.rating-stars .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const value = this.getAttribute('data-value');

            // すべての星をリセット
            stars.forEach(s => s.classList.remove('selected'));

            // 選択された星までハイライト
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('selected');
            }

            // 値をhidden inputにセット
            ratingInput.value = value;
        });
    });

    // 口コミのリアルタイム文字数カウント
    const commentInput = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    const maxChars = 400;

    // 初期状態の文字数表示
    let length = commentInput.value.length;
    charCount.innerHTML = `${length}/${maxChars} <span class="max-text">(最高文字数)</span>`;

    commentInput.addEventListener('input', function () {
        let length = this.value.length;

        // 最大文字数を超えたら制限
        if (length > maxChars) {
            this.value = this.value.substring(0, maxChars);
            length = maxChars;
        }

        // 文字数表示を更新
        charCount.innerHTML = `${length}/${maxChars} <span class="max-text">(最高文字数)</span>`;
    });
});
