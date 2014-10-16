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
                        <th>Mission Name</th>
                        <th>Subject Email</th>
                        <th>Target Tags</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($missions->num_rows > 0){
                        foreach($missions->data as $mission){
                            $tax = '';
                            $tags = $mission->tags;
                            if(sizeof($tags) > 0){
                                $n = 0;
                                foreach($tags as $tag){
                                    if(sizeof($tag) > 0){
                                        foreach($tag as $t){
                                            $tax .= ($n++ > 0)? ', '.$t['tag_name'] : $t['tag_name'];
                                        }
                                    }
                                }
                            }
                            
                            $btedit = array('name'=>'btedit-mission',
                                        'class'=>'btedit-mission btn btn-sm btn-warning',
                                        'var'=>$mission->id,
                                        'content'=>'e');
                            $btdel = array('name'=>'btdel-mission',
                                        'class'=>'btdel-mission btn btn-sm btn-danger',
                                        'var'=>$mission->id,
                                        'content'=>'x');
                        ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?php echo $mission->name; ?></td>
                                <td><?php echo $mission->subject; ?></td>
                                <td><?php echo $tax; ?></td>
                                <td>&nbsp;</td>
                                <td><?php echo status_button($mission->id, $mission->status); ?></td>
                                <td><?php echo form_button($btedit). ' '. form_button($btdel); ?></td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
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
            
            $('.btdel-mission').click(function(){
                bt = $(this);
                conf = confirm('Serius mau dihapus?')
                if(conf){
                    var i = bt.attr('var');
                    if( i > 0 ){
                        url  = '<?php echo site_url(); ?>/mission/remove_mission';
                        data = { 'id':i };
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
            
            $('.mission-status').click(function(){
                btn = $(this);
                data = [{name: 'id', value: btn.attr('var')},{name: 'status', value: btn.attr('stat')}];
                url  = '<?php echo site_url(); ?>/mission/change_status';
                post = $.post(url,data);
                post.done(function(result){
                    var res = $.parseJSON(result);
                    if(res['status']==='success'){
                        dat = res['data'];
                        switch(dat){
                            case 0:
                                btn.attr('stat',dat);
                                btn.removeClass('btn-success').addClass('btn-warning');
                                btn.html('off');
                                break;
                            case 1:
                                btn.attr('stat',dat);
                                btn.removeClass('btn-warning').addClass('btn-success');
                                btn.html('on');
                                break;
                            default:
                                break;
                        }
                    }
                });
            });
        </script>
    </body>
</html>