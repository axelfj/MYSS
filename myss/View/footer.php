<script src="js/vendor/jquery-2.2.4.min.js"></script>
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
<script src="js/tagsinput.js"></script>
<script>
    $( "#comment" ).keyup(function() {
        if($(this).val() != '') {
             $("#commentbtn").removeAttr('disabled');
        }
        else{
            $('#commentbtn').attr("disabled", true);
        }
    });
</script>
</body>
</html>