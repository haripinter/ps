<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Import Contacs</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/fileupload/jquery.fileupload.css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo heading('Import Contacs',2);
        
        $btfile = array('name'=>'contact-upload',
                        'class'=>'btn btn-default fupload',
                        'style'=>'float:left;');
        $upload = array('name'=>'btedit-contact',
                        'class'=>'btedit-contact btn btn-success bupload',
                        'content'=>'Upload');
                        
        echo form_upload($btfile). '&nbsp;' .form_button($upload);
            
        ?>
        <br/>
        <br/>
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <br/>
        <div id="txstatus"></div>
        <div id="txsuccess"></div>
        <div id="txfail"></div>
    
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/fileupload/vendor/jquery.ui.widget.js"></script>
        <script src="<?php echo base_url(); ?>globals/fileupload/jquery.iframe-transport.js"></script>
        <script src="<?php echo base_url(); ?>globals/fileupload/jquery.fileupload.js"></script>
        <script>
            url = '<?php echo site_url(); ?>/contact/upload';
            $('.fupload').fileupload({
                url: url,
                dataType: 'json',
                autoUpload: false,
                add: function(e, data){
                    $('.bupload').click(function(){
                        data.submit();
                    });
                    $('#txsuccess').removeClass().html('');
                    $('#txfail').removeClass().html('');
                },
                submit: function(e, data){
                    $('#txstatus').addClass('bg-info').html('Processing...');
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                },
                done: function(e, data){
                    res  = data.result;
                    
                    $('#txstatus').removeClass('bg-info').html('');
                    succ = $('#txsuccess');
                    succ.addClass('bg-success');
                    succ.append('<h4>Success : '+ res.data.success.length + '</h4><br/>');
                    s = $(res.data.success);
                    /*s.each(function(){
                        succ.append(this + '<br/>');
                    });*/
                    
                    fail = $('#txfail');
                    fail.addClass('bg-warning');
                    fail.append('<h4>Double : '+ res.data.double.length + '</h4><br/>');
                    f = $(res.data.double);
                    f.each(function(){
                        fail.append(this + '<br/>');
                    });
                }
            });
        </script>
    </body>
</html>