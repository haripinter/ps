<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Input Tags</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            echo heading('Input Tags',2);
        ?>
        <?php
        $btadd = array('name'=>'btadd-contact',
                       'class'=>'btadd-contact btn btn-success',
                       'content'=>'Add');
        $btsave = array('name'=>'btsave-contact',
                       'class'=>'btsave-contact btn btn-success',
                       'content'=>'Save');
        echo '<center>'.form_button($btadd).' ';
        echo form_button($btsave).'</center>';
        ?>
        <!--form class="form-input-contact"-->
            <table class="tblist-contact table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tags</th>
                        <th>Tagged</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($tags){
                        foreach($tags->result() as $tag){
                            $btremove = array('name'=>'btremove-contact',
                                            'class'=>'btremove-contact btn btn-danger btn-sm',
                                            'var'=>$tag->id,
                                            'content'=>'x');
                            $btedit = array('name'=>'btedit-contact',
                                            'class'=>'btedit-contact btn btn-warning btn-sm',
                                            'var'=>$tag->id,
                                            'content'=>'e');
                            //$hidden = array('idc[]'=>$tag->id);
                            //form_hidden($hidden)
                            echo '<tr>';
                            echo '<td>&nbsp;</td>';
                            echo '<td><label name="label_name" style="font-weight:normal;">'. $tag->tag_name .'</label></td>';
                            echo '<td>&nbsp;</td>';
                            echo '<td>'. form_button($btedit) . ' '. form_button($btremove) .'</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        <!--/form-->
        
        
        
        <script src="<?php echo base_url(); ?>globals/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>globals/bootstrap/js/bootstrap.min.js"></script>
    
        <script>
            
            function inp_hidden( name, clas, valu ){
                return '<input type="hidden" name="'+ name +'" class="'+ clas +'" value="'+ valu +'">';
            }
            
            function tx_label( name, clas, valu ){
                return '<label name="'+ name +'" class="'+ clas +'">'+ valu +'</label>';
            }
            
            function tx_input( name, clas, valu ){
                return '<input type="text" name="'+ name +'" class="'+ clas +'" value="'+ valu +'">';
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
                            url  = '<?php echo site_url(); ?>/contact/remove_tag';
                            data = { 'iid':i };
                            post = $.post(url,data);
                            post.done(function(result){
                                var res = $.parseJSON(result);
                                if(res['status']==='success'){
                                    remove_tr(bt)
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
            
            $('.btadd-contact').click(function(){
                tBody = $('.tblist-contact tbody');
                isi   = tx_tr(
                            tx_td('') +
                            tx_td(tx_label('label_name','',inp_hidden('idc[]','form-input','') + tx_input('itag_name[]','form-input',''))) +
                            tx_td('') +
                            tx_td(tx_button('','btedit-contact btn btn-warning btn-sm','e') + ' ' +
                                    tx_button('','btremove-contact btn btn-danger btn-sm','x'))
                        );
                
                tBody.append(isi);
                lasttr = last_row(tBody);
                btn = lasttr.find('.btremove-contact');
                act_remove_tr(btn);
                bto = lasttr.find('.btedit-contact');
                act_edit_tag(bto);
            });
            
            
            $('.btsave-contact').click(function(){
                form = $('.form-input');
                data = form.serializeArray();
                url  = '<?php echo site_url(); ?>/contact/post_tag';
                post = $.post(url,data);
                post.done(function(result){
                    var r = $.parseJSON(result);
                    var s = arr_tags(r['success_data']);
                    if(r['status']=='success'){
                        tx = $('.tblist-contact').find('input[name="itag_name[]"]');
                        tx.each(function(){
                            t = $(this);
                            if(t.val()!=''){
                                u = t.parent();
                                v = t.val();
                                w = t.parent().parent().parent().find('.btremove-contact');
                                w.attr('var',s[v]);
                                u.html(v);
                            }
                        });
                    }
                });
            });
            
            function arr_tags( success_data ){
                var ret = [];
                succ = $(success_data);
                succ.each(function(id,tag){
                    d = this;
                    ret[d.tag_name] = d.id;
                });
                return ret;
            }
            
            function find_button_edit( txt, id ){
                bt = txt.parent().parent().parent().find('.btedit-contact');
                bt.attr('var',id);
            }
            
            function act_edit_tag( bt ){
                bt.click(function(){
                    but = $(this);
                    iid = but.parent().find('.btremove-contact').attr('var');
                    lab = but.parent().parent().find('label[name="label_name"]');
                    chx = lab.find('input');
                    if(chx.length < 1){
                        lab.html(inp_hidden('idc[]','form-input',iid) + tx_input('itag_name[]','form-input',lab.html()));
                    }
                });
            }
            
            function first_action(){
                btRemove = $('.tblist-contact .btremove-contact');
                btRemove.each(function(){
                    act_remove_tr($(this))
                });
                
                btEdit = $('.tblist-contact .btedit-contact');
                btEdit.each(function(){
                    act_edit_tag($(this))
                });
            }
            
            first_action();
        </script>
    </body>
</html>