
<?php
include_once "header.php";
include_once "navbar.php";
?>

<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    var start = 0;
    var working = false;
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "data.php?start="+start,
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) {
                r = JSON.parse(r)
                for (var i = 0; i < r.length; i++) {
                    $('body').append(""+
                        "<div class='row'>" +
                        "<div class=col-md-12>" +
                        "<div class=single-amenities>" +
                        "<div class=amenities-details col-md-12>"+
                        "<h3><a>"+r[i].titlePost+"</a></h3>"+
                        "<div class = amenities-meta mb-10>" +
                        "<h7><a>"+r[i].descriptionPost+"</a></h7>" +
                        "</div>"+
                        "<div class=amenities-meta mb-10>" +
                        "<a href=# class=ti-user>"+r[i].username+"</a>" +
                        "<a class=><span class=ti-alarm-clock></span>"+r[i].postTime+"</a>" +
                        "</div>"+
                        "<div class=d-flex justify-content-between mt-20>"+
                        "<div>" +
                        "<a href=# class=blog-post-btn># Comments <span class=ti-comment></span></a>" +
                        "</div>" +
                        "<div class=tag_btn>" +
                        "<a href=#><span class=ti-tag mr-1></span>"+r[i].tag+"</a>" +
                        "</div>"+
                        "</div>" + "</div>" + "</div>" + "</div>" + "</div>")

                }
                start += 4;
            },
            error: function(r) {
                console.log("Something went wrong!");
            }
        })
    })
    $(window).scroll(function() {
        if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
            if (working === false) {
                working = true;
                $.ajax({
                    type: "GET",
                    url: "data.php?start="+start,
                    processData: false,
                    contentType: "application/json",
                    data: '',
                    success: function(r) {
                        r = JSON.parse(r)
                        for (var i = 0; i < r.length; i++) {
                            $('body').append(""+
                                "<div class='row'>" +
                                "<div class=col-md-6>" +
                                "<div class=single-amenities>" +
                                "<div class=amenities-details col-md-12>"+
                                "<h3><a>"+r[i].titlePost+"</a></h3>"+
                                "<div class = amenities-meta mb-10>" +
                                "<h7><a>"+r[i].descriptionPost+"</a></h7>" +
                                "</div>"+
                                "<div class=amenities-meta mb-10>" +
                                "<a href=# class=ti-user>"+r[i].username+" </a>" +
                                "<a class=><span class=ti-alarm-clock></span>"+r[i].postTime+"</a>" +
                                "</div>"+
                                "<div class=d-flex justify-content-between mt-20>"+
                                "<div>" +
                                "<a href=# class=blog-post-btn># Comments <span class=ti-comment></span></a>" +
                                "</div>" +
                                "<div class=tag_btn>" +
                                "<a href=#><span class=ti-tag mr-1></span>"+r[i].tag+"</a>" +
                                "</div>"+
                                "</div>" + "</div>" + "</div>" + "</div>" + "</div>")
                        }
                        start += 4;
                        setTimeout(function() {
                            working = false;
                        }, 4000)
                    },
                    error: function(r) {
                        console.log("Something went wrong!");
                    }
                });
            }
        }
    })
</script>
</body>

<?php
include_once "footer.php";
?>