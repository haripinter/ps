<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Create Message</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/summnote/summernote.css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo heading('Create Message',2);
        
        $msg = array();
        $msg['id'] = '';
        $msg['title'] = '';
        $msg['subject'] = '';
        $msg['message'] = '';
        if($message!='' && $message->num_rows == 1){
            foreach($message->result() as $m){
                $msg['id'] = $m->id;
                $msg['title'] = $m->title;
                $msg['subject'] = $m->subject;
                $msg['message'] = htmlspecialchars_decode($m->message);
            }
        }
        
        ?>
        <div class="col-md-10">
            <button class="msg-back btn btn-default pull-right">Back</button>
        </div>
        <div class="col-md-10">
            <input value="<?php echo $msg['title']; ?>" name="msgtitle" class="msg-title form-control" type="text" style="margin-bottom:5px" placeholder="Title">
            <input value="<?php echo $msg['subject']; ?>" name="msgsubject" class="msg-subject form-control" type="text" style="margin-bottom:5px" placeholder="Email Subject">
            <textarea class="summernote"><?php echo $msg['message']; ?></textarea>
            <button class="msg-save btn btn-default" style="margin-top:5px" var="<?php echo $msg['id']; ?>">Simpan</button>
            &nbsp;<label class="msg-result"></label>
        </div>
        
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/summnote/summernote.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function(){
                $('.summernote').summernote({
                    height: 250
                });
            });
            
            $('.msg-back').click(function(){
                window.history.back();
            });
            
            $('.msg-save').click(function(){
                btn = $(this);
                tit = {name:'title', value:$('.msg-title').val()};
                sub = {name:'subject', value:$('.msg-subject').val()};
                msg = {name:'message', value:$('.summernote').code()};
                idc = {name:'idc', value:btn.attr('var')};
                dat = [idc,msg,tit,sub];
                url = '<?php echo site_url(); ?>/mail/post_message';
                pos = $.post(url,dat);
                pos.done(function(ret){
                    data = $.parseJSON(ret);
                    iid  = data.data.id;
                    btn.attr('var',iid);
                    lbl = $('.msg-result');
                    lbl.html('OK');
                    setTimeout(function(){
                        lbl.html('');
                    },1000)
                });
            });
        </script>
    </body>
</html>