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
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <?php
        echo heading('List Mission',2);
        
        $btadd = array('name'=>'btadd-mission',
                       'class'=>'btadd-mission btn btn-success',
                       'content'=>'Add');
                       
        $btfsender = array('name'=>'btfind-sender',
                       'class'=>'btfind-sender btn btn-info btn-xs pull-right',
                       'var'=>1,
                       'content'=>'<label class="glyphicon glyphicon-search"></label>');
        $btfmaster = array('name'=>'btfind-master',
                       'class'=>'btfind-master btn btn-info btn-xs pull-right',
                       'var'=>1,
                       'content'=>'<label class="glyphicon glyphicon-search"></label>');
        $btftarget = array('name'=>'btfind-target',
                       'class'=>'btfind-target btn btn-info btn-xs pull-right',
                       'var'=>1,
                       'content'=>'<label class="glyphicon glyphicon-search"></label>');
                       
        echo '<center>'.form_button($btadd).'</center>';
        ?>
        <div align="center">
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
                            <label name="label_sender" style="font-weight:normal;"></label>
                            <label name="value_sender"></label>
                            <?php echo form_button($btfsender); ?>
                            <div class="list-sender" style="display:none;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Master Message</td>
                        <td>:</td>
                        <td>
                            <label name="label_master" style="font-weight:normal;"></label>
                            <label name="value_master"></label>
                            <?php echo form_button($btfmaster); ?>
                            <div class="list-master" style="display:none;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Target</td>
                        <td>:</td>
                        <td>
                            <label name="label_target" style="font-weight:normal;"></label>
                            <label name="value_target"></label>
                            <?php echo form_button($btftarget); ?>
                            <div class="list-target" style="display:none;"></div>
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
            $('.btfind-sender').click(function(){
                bt = $(this);
                td = bt.parent();
                
                label = td.find('label[name="label_sender"]');
                value = td.find('label[name="value_sender"]');
                lists = td.find('.list-sender');
                
                url = '<?php echo site_url(); ?>/mission/get_sender';
                pos = $.post(url);
                pos.done(function(result){
                    console.log(result)
                });
            });
        </script>
    </body>
</html>