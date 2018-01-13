<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=1024, initial-scale=1">
    <link rel="canonical" href="http://mosaico.io" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />

    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/knockout.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.ui.touch-punch.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/load-image.all.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/canvas-to-blob.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.iframe-transport.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.fileupload.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.fileupload-process.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.fileupload-image.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/jquery.fileupload-validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/knockout-jqueryui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/evol.colorpicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/mosaico/vendor/tinymce.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/backend/mosaico/mosaico.js?v=0.11"></script>
    <script>
            $(function () {
  if (!Mosaico.isCompatible()) {
    alert('Update your browser!');
    return;
  }
  var ok = Mosaico.init({
                    imgProcessorBackend: '<?= base_url() ?>admin_emails/img',
                    templateBackend: '<?= base_url() ?>admin_emails/tl',
                    emailProcessorBackend: '<?= base_url() ?>admin_emails/dl/',
    titleToken: "MOSAICO Responsive Email Designer",
    fileuploadConfig: {
                        url: '<?= base_url() ?>admin_emails/upload/',
      // messages??
    }
  });
  if (!ok) {
    console.log("Missing initialization hash, redirecting to main entrypoint");
    document.location = ".";
  }
});
    </script>
    
    <?php echo link_tag('assets/backend/mosaico/mosaico-material.css?v=0.10'); ?>
    <?php echo link_tag('assets/backend/mosaico/vendor/notoregular/stylesheet.css'); ?>
    <?php echo link_tag('assets/backend/mosaico/vendor/evol.colorpicker.min.css'); ?>
  </head>
  <body class="mo-standalone">

  </body>
</html>
