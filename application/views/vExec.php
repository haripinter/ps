<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Executor</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            echo heading('Missions Executor',2);
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mission</th>
                    <th>Interval</th>
                    <th>Count</th>
                    <th>Email Sent</th>
                    <th>Progress</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                foreach($missions->result() as $mission){
                ?>
                <tr>
                    <td><?php echo $n++; ?></td>
                    <td><?php echo $mission->name; ?></td>
                    <td><input class="form-input form-control interval<?php echo $mission->id; ?>" style="width:50px" type="text" value="10" maxlength="3" name="interval"></td>
                    <td><input class="form-input form-control count<?php echo $mission->id; ?>" style="width:50px" type="text" value="50" maxlength="3" name="count"></td>
                    <td><label class="email-sent<?php echo $mission->id; ?>"></label></td>
                    <td><label class="progress<?php echo $mission->id; ?>"></label></td>
                    <td><?php echo status_button($mission->id, $mission->status); ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div>Interval in minute, Count -> mail count every request.</div>
        
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script>
            function call_executor( id ){
                intval = $('.interval'+id).val();
                setTimeout(function(){
                    stat = $('.mission-status'+id).attr('stat');
                    if(stat == 1){
                        coun = $('.count'+id).val();
                        data = [{name:'id',value: id},{name:'count', value: coun}];
                        urls  = '<?php echo site_url(); ?>/exec/run';
                        post = $.post(urls,data);
                        post.done(function(result){
                            result = $.parseJSON(result);
                            linfo  = result['last_info'];
                            nn = 0;
                            mm = 0;
                            linfo.forEach(function(r){
                                switch(r.status){
                                    case '1':
                                        nn = parseInt(r.count);
                                        break;
                                    case '0':
                                        mm = parseInt(r.count);
                                        break;
                                    default:
                                        break;
                                }
                            });
                            oo = mm + nn;
                            $('.email-sent'+ id).html( nn +'/'+ oo );
                            $('.progress'+ id).html( (nn/oo*100)+'%' );
                            
                            if(nn < oo){
                                call_executor( id );
                            }
                        });
                    }
                },intval*60*1000);
            }
            
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
                                
                                call_executor(btn.attr('var'));
                                
                                break;
                            default:
                                break;
                        }
                    }
                });
            });
            
            $(document).ready(function(){
                stat = $('.mission-status');
                stat.each(function(){
                    cur = $(this);
                    if(cur.attr('stat') == 1){
                        call_executor( cur.attr('var') );
                    }
                });
                console.log(3)
            });
        </script>
    </body>
</html>