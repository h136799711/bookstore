$(function () {
    console.log("read js");
    // 预读下一章
    function loadNextPage(){
        console.log("loadNextPage");
        var url = window.location.pathname;
        var match = url.match(/\/(\d*)\/(\d*)/);
        if (match.length > 2) {
            var bookId = match[1];
            var pageNo = match[2]++;
            var nextUrl = window.location.protocal + "//" + window.location.host + "/" + bookId + "/" + pageNo + ".html";
            $.get(nextUrl);
        }
    }
    // 3秒后自动进行加载下一章
    setTimeout(loadNextPage, 3000);

})