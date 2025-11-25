;(function (w, d) {

    // ヘッダーがスクロールした時の処理
    let header_scrool_func = () => {
        let siteHeader = document.getElementById('site-header');

        // site-header がない場合は何もしない
        if (!siteHeader) {
            return;
        }

        // PC (画面サイズが 992px 以上) の場合
        if (window.matchMedia("(min-width: 992px)").matches) {
            // スクロール時 (body に header_scrolled クラスがある場合)
            if (document.body.classList.contains('header_scrolled')) {
                // スクロール時は上からメニューが下りてきて固定するので、
                // absolute を解除する
                siteHeader.style.position = null;
            } else {
                // absolute にしてしてページヘッダーに埋め込ませる
                siteHeader.style.position = 'absolute';
            }
        } else {
            // SP ではスクロールに関係なく absolute にしてしてページヘッダーに埋め込ませる
            siteHeader.style.position = 'absolute';
        }
    };
    w.addEventListener('load', header_scrool_func, true);
    w.addEventListener('scroll', header_scrool_func, true);

    let q = null,
        f = function () {
            // ヘッダーの高さ
            let headerHeight = d.getElementsByClassName('site-header')[0].offsetHeight;
            let headerHalf = headerHeight / 2;

            // ページヘッダを取得
            let pageHeader = d.getElementsByClassName('page-header')[0];

            // ページヘッダがある場合
            if (undefined !== pageHeader) {
                // もともとの位置だと埋まってしまうのでヘッダーの高さ分下にオフセット
                Array.prototype.forEach.call(d.getElementsByClassName('page-header-inner'), function (v) {
                    // iPhone では headerHeight の取得が遅れて headerHeight の値が一瞬 2 とかになってしまい、
                    // カクっとするので 10以上の時だけ処理するようにしている
                    if (headerHeight > 10) {
                        v.style.marginTop = headerHeight + 'px';
                    }
                    v.style.opacity = 1;
                });

                let pageHeaderHeight = pageHeader.offsetHeight;
                let generatedPageHeaderHeight = pageHeaderHeight + headerHeight;

                // 最初 page-headerに高さ指定がない時のみ高さを取得して ヘッダーの高さを追加した数値を指定する
                // * 高さ指定がある時に追加してしまうと、ヘッダー高さ分無限に高くなる
                // * 厳密に言えばこの方式はスマホの時に適切なサイズではない事になるが、厳密にいきたい人にはヘッダー高さを手入力して貰う
                Array.prototype.forEach.call(d.getElementsByClassName('page-header'), function (v) {
                    if (!v.style.height) {
                        v.style.height = generatedPageHeaderHeight + 'px';
                        v.style.opacity = 1;
                    }
                });
            }

            // iPhone では headerHalf(headerHeight) の取得が遅れて headerHalf の値が一瞬 2 とかになってしまい、
            // カクっとするので 10以上の時だけ処理
            if (headerHalf > 10) {
                Array.prototype.forEach.call(d.getElementsByClassName('ltg-slide-text-set'), function (v) {
                    // if (w.window.innerWidth < 992) { v.removeAttribute('style'); return; }
                    v.style.top = 'calc(50% + ' + headerHalf + 'px)';
                    v.style.opacity = 1;
                });
                Array.prototype.forEach.call(d.getElementsByClassName('ltg-slide-button-next'), function (v) {
                    v.style.top = 'calc(50% + ' + headerHalf + 'px)';
                    v.style.opacity = 1;
                });
                Array.prototype.forEach.call(d.getElementsByClassName('ltg-slide-button-prev'), function (v) {
                    v.style.top = 'calc(50% + ' + headerHalf + 'px)';
                    v.style.opacity = 1;
                });
            }
        };
    w.addEventListener('load', f, false);
    w.addEventListener('resize', function () {
        clearTimeout(q);
        q = setTimeout(f, 300);
    }, false);

})(window, document);