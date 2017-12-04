$(function () {
    console.log("read js");
    // 预读下一章
    function loadNextPage(){
        console.log("loadNextPage");
        var pageVersion = $("meta[name=page_version]").prop("content");
        var url = window.location.pathname;
        var match = url.match(/\/(\d*)\/(\d*)/);
        if (match.length > 2) {
            var bookId = match[1];
            var pageNo = parseInt(match[2]) + 1;
            var nextUrl = window.location.protocol + "//" + window.location.host + "/" + bookId + "/x/" + (pageNo) + '.html';
            pageVersion = (pageVersion ? pageVersion : -1);
            console.log('current page version', pageVersion);
            $.post(nextUrl, { no_return:1, page_version: pageVersion });
        }
    }
    // 3秒后自动进行加载下一章
    setTimeout(loadNextPage, 3000);

})