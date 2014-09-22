<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mail</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo heading('List Mail',2);
        
        $btadd = array('name'=>'btadd-msg',
                       'class'=>'btadd-msg btn btn-success',
                       'content'=>'Add');
                       
        echo '<center>'.form_button($btadd).'</center>';
        ?>
        <div>
            <table class="tblist-contact table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    foreach($message->result() as $msg){
                        $btremove = array('name'=>'btremove-msg',
                                        'class'=>'btremove-msg btn btn-danger btn-sm',
                                        'var'=>$msg->id,
                                        'content'=>'x');
                        $btedit = array('name'=>'btedit-msg',
                                        'class'=>'btedit-msg btn btn-warning btn-sm',
                                        'var'=>$msg->id,
                                        'content'=>'e');
                                            
                        echo "<tr>";
                        echo "<td>". $n++ ."</td>";
                        echo "<td>". $msg->title ."</td>";
                        echo "<td>". form_button($btedit) . ' '. form_button($btremove) ."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $('.btadd-msg').click(function(){
                id = $(this).attr('var');
                location.href = '<?php echo site_url(); ?>/mail/create/';
            });
            
            $('.btedit-msg').click(function(){
                id = $(this).attr('var');
                location.href = '<?php echo site_url(); ?>/mail/create/'+ id;
            });
            
            $('.btremove-msg').click(function(){
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