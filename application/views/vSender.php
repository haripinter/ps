<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Input Sender</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            echo heading('Input Sender',2);
        ?>
        <?php
        $btadd = array('name'=>'btadd-sender',
                       'class'=>'btadd-sender btn btn-success',
                       'content'=>'Add');
        $btsave = array('name'=>'btsave-sender',
                       'class'=>'btsave-sender btn btn-success',
                       'content'=>'Save');
        echo '<center>'.form_button($btadd).' ';
        echo form_button($btsave).'</center>';
        ?>
        <div>
            Total Sender : <label id="total_sender"><?php echo $total_sender; ?></label>
        </div>
        <!--form class="form-input-sender"-->
            <table class="tblist-sender table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Server</th>
                        <th>Email</th>
                        <th>Pass</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($sender){
                        foreach($sender->result() as $send){
                            $btremove = array('name'=>'btremove-sender',
                                            'class'=>'btremove-sender btn btn-danger btn-sm',
                                            'var'=>$send->id,
                                            'content'=>'x');
                            $btedit = array('name'=>'btedit-sender',
                                            'class'=>'btedit-sender btn btn-warning btn-sm',
                                            'var'=>$send->id,
                                            'content'=>'e');
                            $btfind = array('name'=>'btvietags-sender',
                                            'class'=>'btvietags-sender btn btn-info btn-xs pull-right',
                                            'var'=>$send->id,
                                            'style'=>'display:none;',
                                            'content'=>'<label class="glyphicon glyphicon-search"></label>');
                            echo '<tr>';
                            echo '<td>&nbsp;</td>';
                            echo '<td><label name="label_server" style="font-weight:normal;">'. 
                                        '<label class="select-server" var="'. $send->server .'" style="font-weight:normal;">'. $send->server_name .'</label>'
                                    .'</label></td>';
                            echo '<td><label name="label_email" style="font-weight:normal;">'. $send->email .'</label></td>';
                            echo '<td><label name="label_pass" style="font-weight:normal;">'. $send->password .'</label></td>';
                            echo '<td>'. form_button($btedit) . ' '. form_button($btremove) .'</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        <!--/form-->
        
        <input type="hidden" class="servers" value="<?php echo htmlspecialchars($server); ?>">
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
    
        <script>
            
            function inp_hidden( name, clas, valu ){
                return '<input type="hidden" name="'+ name +'" class="'+ clas +'" value="'+ valu +'">';
            }
            
            function tx_label( name, clas, valu ){
                return '<label name="'+ name +'" class="'+ clas +'">'+ valu +'</label>';
            }
            
            function tx_label_var(clas, far, valu){
                return '<label var="'+ far +'" class="'+ clas +'">'+ valu +'</label>';
            }
            
            function tx_input( name, clas, valu ){
                return '<input type="text" name="'+ name +'" class="'+ clas +'" value="'+ valu +'">';
            }
            
            function tx_password( name, clas, valu ){
                return '<input type="password" name="'+ name +'" class="'+ clas +'" value="'+ valu +'">';
            }
            
            function tx_button( name, clas, cont ){ 
                return '<button name="'+ name +'" class="'+ clas +'">'+ cont +'</button>';
            }
            
            function tx_td( inp ){
                return '<td>'+ inp +'</td>';
            }
            
            function tx_tr( inp ){
                return '<tr>'+ inp +'</tr>';
            }
            
            function tx_option( name, clas, data, inp ){
                html = '<select name="'+ name +'" class="'+ clas +'">';
                data = $.parseJSON(data);
                $(data).each(function(){
                    dd = this;
                    sl = '';
                    if(inp==dd.id) sl = 'selected';
                    html += '<option value="'+ dd.id +'" '+ sl +'>'+ dd.server +'</option>';
                });
                html += '</select>';
                return html;
            }
            
            function last_row( tbd ){
                tmp = tbd.children();
                return $(tmp[tmp.length-1]);
            }
            
            function remove_tr( bt ){
                bt.parent().parent().remove();
            }
            
            function act_remove_tr( bt ){
                bt.click(function(){
                    conf = confirm('Serius mau dihapus?')
                    if(conf){
                        var i = bt.attr('var');
                        if( i > 0 ){
                            url  = '<?php echo site_url(); ?>/sender/remove_sender';
                            data = { 'id':i };
                            post = $.post(url,data);
                            post.done(function(result){
                                var res = $.parseJSON(result);
                                if(res['status']==='success'){
                                    remove_tr(bt);
                                    $('#total_sender').html(res['total_sender']);
                                }
                            });
                        }else{
                            remove_tr(bt);
                        }
                    }else{
                        return;
                    }
                });
            }
            
            $('.btadd-sender').click(function(){
                tBody = $('.tblist-sender tbody');
                sData = $('.servers').val();
                isi   = tx_tr(
                            tx_td('') +
                            tx_td(tx_label('label_server','', tx_option('iserver[]','form-input',sData,''))) +
                            tx_td(tx_label('label_email','', inp_hidden('ids[]','form-input','') + tx_input('iemail[]','form-input',''))) +
                            tx_td(tx_label('label_pass','', tx_input('ipass[]','form-input',''))) +
                            tx_td(tx_button('','btedit-sender btn btn-warning btn-sm','e') + ' ' +
                                    tx_button('','btremove-sender btn btn-danger btn-sm','x'))
                        );
                
                tBody.append(isi);
                lasttr = last_row(tBody);
                btn = lasttr.find('.btremove-sender');
                act_remove_tr(btn);
                bto = lasttr.find('.btedit-sender');
                act_edit_tag(bto);
            });
            
            $('.btsave-sender').click(function(){
                form = $('.form-input');
                data = form.serializeArray();
                url  = '<?php echo site_url(); ?>/sender/post_sender';
                post = $.post(url,data);
                post.done(function(result){
                    var r = $.parseJSON(result);
                    var sucs = r['success_data'];
                    if(r['status']=='success'){
                        tx = $('.tblist-sender').find('input[name="iemail[]"]');
                        tx.each(function(){
                            t = $(this);
                            v = t.val();
                            
                            if(v!=''){
                                theData = $.map(sucs, function(suc) {
                                            return suc.email == v ? suc : null;
                                        })[0];
                                tr = t.parent().parent().parent();
                                
                                nam = tr.find('select[name="iserver[]"]');
                                nav = nam.val();
                                nam.parent().html( tx_label_var('select-server',theData.server,theData.server_name) );
                                
                                nam = tr.find('input[name="ipass[]"]');
                                nav = nam.val();
                                nam.parent().html(nav);
                                
                                // button
                                but = tr.find('.btremove-sender');
                                but.attr('var',theData.id);
                                
                                btf = tr.find('.btvietags-sender');
                                btf.attr('style','display:none;');
                                
                                u = t.parent().html(v);
                            }
                        });
                        $('#total_sender').html(r['total_sender']);
                    }
                });
            });
            
            function act_edit_tag( bt ){
                bt.click(function(){
                    but = $(this);
                    iid = but.parent().find('.btremove-sender').attr('var');
                    trr = but.parent().parent();
                    
                    sData = $('.servers').val();
                    
                    btf =  trr.find('.btvietags-sender');
                    btf.attr('style','display:block;');
                    
                    // option server
                    nam = trr.find('label[name="label_server"]');
                    tx1 = nam.find('label[name="iserver[]"]');
                    vol = trr.find('.select-server');
                    ver = vol.attr('var');
                    if(tx1.length < 1){
                        nam.html( tx_option('iserver[]','form-input',sData,ver) );
                    }
                    
                    // textfield email
                    eml = trr.find('label[name="label_email"]');
                    tx2 = eml.find('input[name="iemail[]"]')
                    if(tx2.length < 1){
                        eml.html(inp_hidden('ids[]','form-input',iid) + tx_input('iemail[]','form-input',eml.html()));
                    }
                    
                    // textfield pass
                    eml = trr.find('label[name="label_pass"]');
                    tx2 = eml.find('input[name="ipass[]"]')
                    if(tx2.length < 1){
                        eml.html(tx_input('ipass[]','form-input',eml.html()));
                    }
                });
            }
            
            function first_action(){
                btRemove = $('.tblist-sender .btremove-sender');
                btRemove.each(function(){
                    act_remove_tr($(this));
                });
                
                btEdit = $('.tblist-sender .btedit-sender');
                btEdit.each(function(){
                    act_edit_tag($(this));
                });
            }
            
            first_action();
        </script>
    </body>
</html>