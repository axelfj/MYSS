<?php
include_once "header.php";
include_once "navbar.php";
?>

<!--================ Start Blog Post Area =================-->
<section class="blog-post-area ">
    <div class="container">
        <div class="row">
            <div id="postDiv" class="col-md-8">
                <script src="js/vendor/jquery-2.2.4.min.js"></script>
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
                                    $('#postDiv').append(""+
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
                                            $('#postDiv').append(""+
                                                "<div class='row'>" +
                                                "<div class=col-md-12>" +
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

            <!-- Start Blog Post Sidebar -->
            <div class="col-md-4 sidebar-widgets"  >
                <div class="widget-wrap" style="position: absolute;width: 300px; left: 800px">

                    <div class="single-sidebar-widget post-category-widget" >
                        <h4 class="ti-tag"> Popular Tags </h4>
                        <ul class="cat-list mt-20">
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Fashion</p>
                                    <p>59</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Travel</p>
                                    <p>09</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Lifestyle</p>
                                    <p>24</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Shopping</p>
                                    <p>44</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Food</p>
                                    <p>15</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Blog Post Siddebar -->
    </div>

</section>
<!--================ End Blog Post Area =================-->

<?php
include_once "footer.php";
?>
