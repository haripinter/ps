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
            .list-of-master, .list-of-target{
                list-style: none;
                padding-left: 0px;
            }
        </style>
    </head>
    <body>
        <?php
        echo heading('List Mission',2);
        
        $btsave = array('name'=>'btsave-mission',
                       'class'=>'btsave-mission btn btn-success',
                       'content'=>'Save');
        $btfmaster = array('name'=>'btfind-master',
                       'class'=>'btfind-master btn btn-info btn-xs pull-right',
                       'var'=>1,
                       'content'=>'<label class="glyphicon glyphicon-search"></label>');
        $btftarget = array('name'=>'btfind-target',
                       'class'=>'btfind-target btn btn-info btn-xs pull-right',
                       'var'=>1,
                       'content'=>'<label class="glyphicon glyphicon-search"></label>');
                       
        //echo '<center>'.form_button($btadd).'</center>';
        ?>
        <div align="center">
            <table class="form-mission">
                <tbody>
                    <tr>
                        <td width="150px" valign="top">Mission</td>
                        <td width="20px" valign="top">:</td>
                        <td><input type="text" name="val_title" class="form-control form-input" placeholder="Mission Name"></td>
                    </tr>
                    <tr>
                        <td valign="top">Sender</td>
                        <td valign="top">:</td>
                        <td>
                            <label name="label_sender" style="font-weight:normal;">
                                <?php echo select_sender('val_sender','form-control form-input',$sender); ?>
                            </label>
                            <label name="value_sender"></label>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px" valign="top">Subject Message</td>
                        <td width="20px" valign="top">:</td>
                        <td><input type="text" name="val_subject" class="form-control form-input" placeholder="Subject"></td>
                    </tr>
                    <tr>
                        <td valign="top">Master Message</td>
                        <td valign="top">:</td>
                        <td>
                            <label name="label_master" style="font-weight:normal;"></label>
                            <label name="value_master">
                                <input type="hidden" class="form-input" name="val_master" value="0">
                            </label>
                            <?php echo form_button($btfmaster); ?>
                            <div class="list-master" style="display:none;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Target</td>
                        <td valign="top">:</td>
                        <td>
                            <label name="label_target" style="font-weight:normal;"></label>
                            <label name="value_target">
                                <input type="hidden" class="form-input" name="val_target" value="[]">
                            </label>
                            <?php echo form_button($btftarget); ?>
                            <div class="list-target" style="display:none;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Schedule</td>
                        <td valign="top">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td valign="top"></td>
                        <td valign="top"></td>
                        <td>
                            <div style="float:right">
                            <?php
                                echo form_button($btsave);
                            ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
        <script>
            // data must be array id,email
            function tx_radio_sender(data, inp){
                html = '<ul class="list-of-master">';
                data.forEach(function(det){
                    html += '<li>'
                    html += '<input type="radio" value="'+ det.id +'" name="tx_sender">&nbsp;'+ det.title +'<br\>';
                    html += '</li>'
                });
                html += '</ul>';
                return html;
            }
            
            function tx_check_target(data, inp){
                html = '<ul class="list-of-target">';
                data.forEach(function(det){
                    html += '<li>'
                    html += '<input type="checkbox" value="'+ det.id +'" name="tx_target">&nbsp;'+ det.tag_name +'<br\>';
                    html += '</li>'
                });
                html += '</ul>';
                return html;
            }
            
            function tx_option_sender(data, inp){
                html = '<select name="tx_sender" class="form-input">';
                $(data).each(function(){
                    dd = this;
                    sl = '';
                    if(inp==dd.id) sl = 'selected';
                    html += '<option value="'+ dd.id +'" '+ sl +'>'+ dd.email +'</option>';
                });
                html += '</select>';
                return html;
            }
            
            $('.btsave-mission').click(function(){
                form = $('.form-input');
                data = form.serializeArray();
                url  = '<?php echo site_url(); ?>/contact/post_contact';
                
                console.log(data)
            });
            
            $('.btfind-master').click(function(){
                bt = $(this);
                td = bt.parent();
                
                label = td.find('label[name="label_master"]');
                value = td.find('label[name="value_master"]');
                lists = td.find('.list-master');
                
                url = '<?php echo site_url(); ?>/mission/get_message';
                pos = $.post(url);
                pos.done(function(result){
                    data = $.parseJSON(result);
                    if(data['status']=='success'){
                        arr = data['data'];
                        lists.html(tx_radio_sender(arr, ''));
                        
                        rad = lists.find('input[name="tx_sender"]');
                        rad.each(function(){
                            act_radio( $(this) );
                        });
                        
                        lists.slideDown();
                    }
                });
            });
            
            $('.btfind-target').click(function(){
                bt = $(this);
                td = bt.parent();
                
                label = td.find('label[name="label_target"]');
                value = td.find('label[name="value_target"]');
                lists = td.find('.list-target');
                
                url = '<?php echo site_url(); ?>/mission/get_target';
                pos = $.post(url);
                pos.done(function(result){
                    data = $.parseJSON(result);
                    if(data['status']=='success'){
                        arr = data['data'];
                        lists.html(tx_check_target(arr, ''));
                        
                        rad = lists.find('input[name="tx_target"]');
                        rad.each(function(){
                            act_check( $(this) );
                        });
                        
                        lists.slideDown();
                    }
                });
            });
            
            function act_check( rad ){
                rad.click(function(){
                    t = $('.form-mission').find('input[name="val_target"]');
                    v = this.value;
                    w = t.val();
                    w = $.parseJSON(w);
                    i = w.indexOf(v);
                    if(this.checked){
                        if( i < 0 ) w.push(v); 
                    }else{
                        if( i >= 0 ) w.splice(i,1);
                    }
                    t.val( JSON.stringify(w) );
                    console.log(w)
                });
            }
            
            function act_radio( rad ){
                rad.click(function(){
                    t = $('.form-mission').find('input[name="val_master"]');
                    v = this.value;
                    t.val(v);
                });
            }
        </script>
    </body>
</html>