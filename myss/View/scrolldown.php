
<?php
include_once "header.php";
include_once "navbar.php";
?>

<body>
<section  class="blog-post-area ">
    <div class="container">
        <div class="row">
            <div id="parent" class="col-md-8">

                <script>
                    // Create the new node to insert
                    var newNode = document.createElement("span");

                    // Get a reference to the parent node
                    var parentDiv = document.getElementById("child").parentNode;

                    // Begin test case [ 1 ] : Exist a childElement --> All working correctly
                    var sp2 = document.getElementById("child");
                    parentDiv.insertBefore(newNode, sp2);
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
                                    $('main').append(""+
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
                                            $('main').append(""+
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
                                        parentDiv.insertBefore(newNode, sp2);
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
                <div class="col-md-4 sidebar-widgets">
                    <div class="widget-wrap">

                        <!-- Search Bar -->
                        <div class="single-sidebar-widget search-widget">
                            <form class="search-form" action="#">
                                <input placeholder="Search Posts" name="search" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search Posts'">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>

                        <div class="single-sidebar-widget post-category-widget">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"
></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/jquery.tabs.min.js"></script>
<script src="js/parallax.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery.ajaxchimp.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script
        type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"
></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/main.js"></script>
</body>
