<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Create Mission</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
        <style>
            .form-mission td{
                padding: 3px;
            }
        </style>
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
            <table class="form-mission">
                <tbody>
                    <tr>
                        <td width="150px">Mission</td>
                        <td width="20px">:</td>
                        <td><input type="text" class="form-control" placeholder="Mission Name"></td>
                    </tr>
                    <tr>
                        <td>Sender</td>
                        <td>:</td>
                        <td>
                            <div></div>
                            <button class="btn btn-info btn-xs pull-right"><i class="glyphicon glyphicon-search"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Master Message</td>
                        <td>:</td>
                        <td>
                            <div></div>
                            <button class="btn btn-info btn-xs pull-right"><i class="glyphicon glyphicon-search"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Target</td>
                        <td>:</td>
                        <td>
                            <div></div>
                            <button class="btn btn-info btn-xs pull-right"><i class="glyphicon glyphicon-search"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Schedule</td>
                        <td>:</td>
                        <td></td>
                    </tr>
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