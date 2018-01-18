</div>
</div>
</div> <!-- / #content-wrapper -->
<div id="main-menu-bg"></div>
</div> <!-- / #main-wrapper -->



<!-- Pixel Admin's javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/backend/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/backend/js/wow.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/backend/js/pixel-admin.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/backend/tag-it/js/tag-it.js"></script>

<script type="text/javascript">

    function display_c() {
        var refresh = 1000; // Refresh rate in milli seconds
        mytime = setTimeout('display_ct()', refresh)
    }

    function display_ct() {
        var strcount
        var x = new Date()
        document.getElementById('ct').innerHTML = x;
        tt = display_c();
    }

    $(document).ready(function () {

        // Preloader
        $(window).load(function () {
            $('.preloader').fadeOut();
        });

        // Initiat WOW.js
        var wow = new WOW(
                {
                    mobile: false
                }
        );
        wow.init();
        display_c();

        $('#campaignTags').tagit({
            singleField: true,
            singleFieldNode: $('#campaignTagsSingleField')
        });

    });


    window.PixelAdmin.start(init);

    var mosaico_img_url = "<?php echo base_url(); ?>assets/backend/mosaico";

</script>

</body>
</html>