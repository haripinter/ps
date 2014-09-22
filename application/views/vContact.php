<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Input Contact</title>
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>globals/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            echo heading('Input Contact',2);
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
        <div>
            Total Email : <?php echo $total_email; ?>
        </div>
        <!--form class="form-input-contact"-->
            <table class="tblist-contact table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Tagged</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($contact){
                        foreach($contact->result() as $cont){
                            $btremove = array('name'=>'btremove-contact',
                                            'class'=>'btremove-contact btn btn-danger btn-sm',
                                            'var'=>$cont->id,
                                            'content'=>'x');
                            $btedit = array('name'=>'btedit-contact',
                                            'class'=>'btedit-contact btn btn-warning btn-sm',
                                            'var'=>$cont->id,
                                            'content'=>'e');
                            $btfind = array('name'=>'btvietags-contact',
                                            'class'=>'btvietags-contact btn btn-info btn-xs pull-right',
                                            'var'=>$cont->id,
                                            'content'=>'<label class="glyphicon glyphicon-search"></label>');
                            echo '<tr>';
                            echo '<td>&nbsp;</td>';
                            echo '<td><label name="label_name" style="font-weight:normal;">'. $cont->name .'</label></td>';
                            echo '<td><label name="label_email" style="font-weight:normal;">'. $cont->email .'</label></td>';
                            echo '<td>'.
                                    '<label name="label_tags" style="font-weight:normal;">'. decode_json_tags($cont->tags) .'</label>'.
                                    '<label name="label_value" var="'. htmlspecialchars($cont->tags) .'"></label>'.
                                    form_button($btfind) . 
                                    '<div class="list-tags" style="display:none;">'. tags_name( $tags_name ) .'</div>'.
                                 '</td>';
                            echo '<td>'. form_button($btedit) . ' '. form_button($btremove) .'</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        <!--/form-->
        
        <input type="hidden" class="tags_name" value="<?php echo htmlspecialchars($tags_name); ?>">
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
            
            function tags_name(){
                //html = '<div style="height: 100px; overflow-y: scroll;">';
                html = '<div>';
                tags = $.parseJSON($('.tags_name').val());
                $(tags).each(function(){
                    html += '<input type="checkbox" class="kotak_tag"> ' + this.tag_name + '<br/>'
                });
                html += '</a>';
                
                return html;
            }
            
            function act_remove_tr( bt ){
                bt.click(function(){
                    conf = confirm('Serius mau dihapus?')
                    if(conf){
                        var i = bt.attr('var');
                        if( i > 0 ){
                            url  = '<?php echo site_url(); ?>/contact/remove_contact';
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
                            tx_td(tx_label('label_name','',tx_input('iname[]','form-input',''))) +
                            tx_td(tx_label('label_email','',inp_hidden('idc[]','form-input','') + tx_input('iemail[]','form-input',''))) +
                            tx_td(
                                tx_label('label_tags','pull-left',inp_hidden('itag[]','form-input','')) +
                                tx_label('label_value','pull-left','') +
                                tx_button('','btvietags-contact btn btn-info btn-xs pull-right','<label class="glyphicon glyphicon-search"></label>') +
                                '<div class="list-tags" style="display:none;">'+ tags_name() +'</div>'
                                ) +
                            tx_td(tx_button('','btedit-contact btn btn-warning btn-sm','e') + ' ' +
                                    tx_button('','btremove-contact btn btn-danger btn-sm','x'))
                        );
                
                tBody.append(isi);
                lasttr = last_row(tBody);
                btn = lasttr.find('.btremove-contact');
                act_remove_tr(btn);
                bto = lasttr.find('.btedit-contact');
                act_edit_tag(bto);
                btv = lasttr.find('.btvietags-contact');
                act_view_tag(btv);
                chk = lasttr.find('.kotak_tag');
                $(chk).each(function(){
                    //console.log(this)
                });
            });
            
            $('.kotak_tag').click(function(){
                //console.log(this.checked)
            });
            
            $('.btsave-contact').click(function(){
                form = $('.form-input');
                data = form.serializeArray();
                url  = '<?php echo site_url(); ?>/contact/post_contact';
                post = $.post(url,data);
                post.done(function(result){
                    var r = $.parseJSON(result);
                    var s = arr_tags(r['success_data']);
                    if(r['status']=='success'){
                        tx = $('.tblist-contact').find('input[name="iemail[]"]');
                        tx.each(function(){
                            t = $(this);
                            v = t.val();
                            
                            if(v!=''){
                                tr = t.parent().parent().parent();
                                
                                nam = tr.find('input[name="iname[]"]');
                                nav = nam.val();
                                nam.parent().html(nav);
                                
                                tag = tr.find('input[name="itag[]"]');
                                fff = tag.val();
                                tag.parent().html(fff);
                                
                                // button
                                but = tr.find('.btremove-contact');
                                but.attr('var',s[v]);
                                
                                u = t.parent().html(v);
                            }
                        });
                    }
                });
            });
            
            function act_view_tag( bt ){
                bt.click(function(){
                    bu = $(this).parent().find('.list-tags');
                    bu.slideToggle();
                });
            }
            
            function arr_tags( success_data ){
                var ret = [];
                succ = $(success_data);
                succ.each(function(id,tag){
                    d = this;
                    ret[d.email] = d.id;
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
                    trr = but.parent().parent();
                    
                    // textfield name
                    nam = trr.find('label[name="label_name"]');
                    tx1 = nam.find('input[name="iname[]"]');
                    if(tx1.length < 1){
                        nam.html(tx_input('iname[]','form-input',nam.html()));
                    }
                    
                    // textfield email
                    eml = trr.find('label[name="label_email"]');
                    tx2 = eml.find('input[name="iemail[]"]')
                    if(tx2.length < 1){
                        eml.html(inp_hidden('idc[]','form-input',iid) + tx_input('iemail[]','form-input',eml.html()));
                    }
                    
                    // tags
                    tag = trr.find('label[name="label_tags"]');
                    tx2 = eml.find('input[name="itag[]"]')
                    if(tx2.length < 1){
                        tag.html(inp_hidden('itag[]','form-input',tag.html()));
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
                
                btTags = $('.tblist-contact .btvietags-contact');
                btTags.each(function(){
                    act_view_tag($(this))
                });
            }
            
            first_action();
        </script>
    </body>
</html>