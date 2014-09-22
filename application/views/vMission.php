<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mission</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo heading('List Mission',2);
        
        $btadd = array('name'=>'btadd-mission',
                       'class'=>'btadd-mission btn btn-success',
                       'content'=>'Add');
                       
        echo '<center>'.form_button($btadd).'</center>';
        ?>
        <div>
            <table class="tblist-mission table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Mission</th>
                        <th>Mail Targets</th>
                        <th>Configuration</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $('.btadd-mission').click(function(){
                id = $(this).attr('var');
                location.href = '<?php echo site_url(); ?>/mission/create/';
            });
            
            $('.btedit-mission').click(function(){
                id = $(this).attr('var');
                location.href = '<?php echo site_url(); ?>/mission/create/'+ id;
            });
            
            $('.btremove-mission').click(function(){
                bt = $(this);
                conf = confirm('Serius mau dihapus?')
                if(conf){
                    var i = bt.attr('var');
                    if( i > 0 ){
                        url  = '<?php echo site_url(); ?>/mail/remove_message_out';
                        data = { 'iid':i };
                        post = $.post(url,data);
                        post.done(function(result){
                            var res = $.parseJSON(result);
                            if(res['status']==='success'){
                                bt.parent().parent().remove();
                            }
                        });
                    }
                }
            });
        </script>
    </body>
</html>