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
    $(document).on('keyup', '.classComment', function () {
        var id = $(this).attr('id');
        var len = id.lenght;
        if (id.includes('comment')) {
            var button = '#commentbtn';
            var num = id.slice(7, len);
        } else {
            var button = '#answerbtn';
            var num = id.slice(6, len);
        }

        if ($(this).val() != '') {
            $(button + num).removeAttr('disabled');
        }
        else {
            $(button + num).attr("disabled", true);
        }
    });
    $(".imgAdd").click(function () {
        $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
    });
    $(document).on("click", "i.del", function () {
        $(this).parent().remove();
    });
    $(function () {
        $(document).on("change", ".uploadFile", function () {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function () { // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                    uploadFile.closest(".imgUp").find('.imagePreview').css("display", "block");
                }
            }

        });
    });
    $('.prevent').click(function (event) {
        event.preventDefault();
    });

    function toggleDivAnswer(className) {
        var divElements = document.getElementsByClassName(className);

        Array.prototype.forEach.call(divElements, function (element) {
            if (element.style.display == 'none') {
                element.style.display = 'block';
            }
            else {
                element.style.display = 'none';
            }
        });

    }

</script>
</body>
</html>