<?php
$em = Local::getEM();
?>
<style type="text/css">
.table-striped tbody > tr:nth-child(odd) > td,
.table-hover tbody tr:hover > td
{
    background: none;
}

.yellow-bg {
    background-color: #FEFCCB;
}
.red-background-remark {
    background: #FFD2D3;
}
</style>
<div class='row-fluid'>
    <div class='span12'>
        <div class='page-header'>
            <h1 class='pull-left'>
                <i class='icon-list'></i>
                <span>User queue list</span>
            </h1>
            <div class='pull-right'>
                <div class='btn-group'>
                    <a href="index.php?page=department/list" class="btn btn-success"><i class='icon-chevron-left'></i>
                        Back to config
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var sc = $('#showScan');
        var now_hn_id;
        $('form#formScan').submit(function(e){
            e.preventDefault();

            var dep_id = 0;
            var hn_id = $('#search', this).val();

            // cut 00
            hn_id = +hn_id;
            hn_id = "" + hn_id;

            /*
             if(hn_id == now_hn_id){
             $('.s-call-btn', sc).click();
             return;
             }
             */

            now_hn_id = hn_id;

            // scan and skip
            if(hn_id==99999999){
                $('.s-skip-btn', sc).click();
                $('#search', this).val('');
                sc.slideUp();
                return;
            }

            var trQ = $('.que-tr[hn_id="'+ hn_id +'"]');
            if(trQ.size()==0){
                return;
            }

            $('.s-name', sc).text($('.name', trQ).text());
            var img = $('.s-img', sc);
            img.unbind('error');
            img.error(function(e){
                $(this).attr('src', 'http://placehold.it/250x150');
            });
            var pathImg  =  'public/img/users/'+hn_id+'.bmp';
            $(img).attr('src', pathImg);

            $('.s-call-btn', sc).unbind('click.call').bind('click.call', function(e){
                e.preventDefault();
                $('.call-btn', trQ).click();
            });

            $('.s-skip-btn', sc).unbind('click.skip').bind('click.skip', function(e){
                e.preventDefault();
                $('.skip-btn', trQ).click();
                sc.slideUp();
            });

            $('.hide-btn span').unbind('update-text');
            $('.s-hide-btn', sc).unbind('click.hide').bind('click.hide', function(e){
                e.preventDefault();
                $('.hide-btn', trQ).click()
            }).find('span').text($('.hide-btn', trQ).text());

            $('.hide-btn', trQ).find('span').bind('update-text', function(){
                $('.s-hide-btn span', sc).text($(this).text());
            });

            $('.s-remark-input', sc).unbind('keyup.remark').bind('keyup.remark', function(e){
                //e.preventDefault();
                $('.remark-input', trQ).val($('.s-remark-input', sc).val());
                if(e.which==13){
                    e.preventDefault();
                    $('.s-remark-btn', sc).click();
                }
            }).val($('.remark-input', trQ).val());

            $('.s-remark-btn', sc).unbind('click.remark').bind('click.remark', function(e){
                e.preventDefault();
                $('.remark-btn', trQ).click();
            });

            $('.s-call-btn', sc).click();

            $('#showScan').show();
            $('#search', this).val('');
        });

        /*
         $("#search").keypress(function(e){
         if(e.which == 13){
         e.preventDefault();
         $('form#formScan').submit();
         }
         });
         */

        $(window).keydown(function(e) {
            if (e.keyCode == 120) {
                $("#search").focus();
                return;
            }

            var tag = e.target.tagName.toLowerCase();
            if ( (tag != 'input' && tag != 'textarea') || e.target.id=='search'){
                if (e.which===117) {
                    userAction('call');
                } else if (e.which===118) {
                    userAction('skip');
                } else if (e.which===119) {
                    userAction('hide');
                }
            }

            function userAction(action) {
                e.preventDefault();
                if (action==='skip') {
                    // Do some script
                    $('.s-skip-btn', sc).click();

                }
                else if (action==='hide') {
                    // Do some script
                    $('.s-hide-btn', sc).click();

                }
                else if(action==='call'){
                    // Do call
                    $('.s-call-btn', sc).click();

                }
            }
        });
    });

</script>
<div class="row-fluid">
    <div class='span12 box'>
        <div class="box-header red-background">
            <div class="title">
                <i class='icon-search'></i> Scan barcode
            </div>
            <div class="actions">
                <a href="#" class="btn box-collapse btn-mini btn-link"><i></i> </a>
            </div>
        </div>

        <div class="box-content">
            <div class="row-fluid">
                <div class="span6" style="margin-bottom: -20px;padding-top: 10px;">
                    <form action="#" method="post" id="formScan">
                        <input type="text" name="search" style="margin: 0;" id="search">
                        <button class="btn">Scan</button>
                    </form>
                </div>
                <div class="span6 text-right">
                    <div class="">
                        <script type="text/javascript">
                            $(function(){
                                $('#select_department').change(function(e){
                                    var val = $(this).val();
                                    if(val.toLowerCase()=='all'){
                                        $('.que-tr').show();
                                        return;
                                    }
                                    $('.que-tr').hide();
                                    $('.que-tr[pt_type="'+val+'"]').show();
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
                
            <div class="row-fluid" id="showScan" style="display:none;">
                <div class="span6 text-center">
                    <h1 class="s-name"></h1>
                    <img alt="250x150" class="s-img" src="">
                </div>
                <div class="span6">
                    <div class='text-center' style="padding-top: 50px">
                        <a class='btn btn-success btn-large s-call-btn' href='#'>
                            <i class='icon-bullhorn'></i>
                            Call
                        </a>
                        <a class='btn btn-large s-skip-btn'>
                            <i class='icon-mail-forward'></i>
                            Skip
                        </a>
                        <a class='btn btn-danger btn-large s-hide-btn'>
                            <i class='icon-remove'></i>
                            <span>Hide</span>
                        </a>
                        <input class="s-remark-input">
                        <a class='btn btn-info s-remark-btn'>
                            <i class='icon-info'></i>
                            Save
                        </a>
                    </div>
                </div>
            </div>
            <div class="form-actions" style="font-size: 20px;">
                <i class="icon-comment"></i>&nbsp;&nbsp;&nbsp; F9 = Focus textbox , F6 = Call , F7 = Skip , F8 = Hide
            </div>
            
        </div>
    </div>
</div>

<div class='row-fluid'>
    <div class='span12 box'>
        <div class='box-header red-background'>
            <div class='title'><i class='icon-time'></i> User queue</div>
        </div>
        <div class='box-content'>


            <div>
                <span>Department</span>
                <select id="select_department">
                    <option value="All">All</option>
                    <option value="ทั่วไป">ทั่วไป</option>
                    <option value="FollowUp">FollowUp</option>
                    <option value="Chronic">Chronic</option>
                    <option value="ตรวจสุขภาพ">ตรวจสุขภาพ</option>
                    <option value="เฉพาะทาง">เฉพาะทาง</option>
                    <!--<option value="เด็ก">เด็ก</option>-->
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>

                <a class="btn" href="#" onclick="window.open('index.php?page=user/show', '', 'width=400, height='+screen.height);">เรียกหน้าต่างแสดงคิวแบบเล็ก</a>
                <div style="float:right">
                <form class="hide-many-form" style="display: inline-block;">
                    <input type="hidden" name="page" value="user/clear_dru">
                    Hide รายชื่อที่อยุ่ในคิวนานกว่า
                    <select name="minute" id="hide-minute">
                        <option value="30">30 นาที</option>
                        <option value="60">60 นาที</option>
                    </select>
                    <button type="submit">Hide</button>
                </form>
                </div>
            </div>

            <div>
                call room
                <select id="suffix" name="suffix">
                    <option value="1">ห้องตรวจ 1</option>
                    <option value="2">ห้องตรวจ 2</option>
                    <option value="3">ห้องตรวจ 3</option>
                    <option value="4">ห้องตรวจ 4</option>
                    <option value="5">ห้องตรวจ 5</option>
                    <option value="6">ห้องตรวจ 6</option>
                    <option value="7">ห้องตรวจ 7</option>
                    <option value="8">ห้องตรวจ 8</option>
                </select>
            </div>

            <div class='tabbable' style='margin-top: 20px'>
                <ul class='nav nav-responsive nav-tabs'>
                    <li class='active'>
                        <a data-toggle='tab' href='#showUsers'>
                            <i class="icon-ok-sign"></i> Show
                        </a>
                    </li>
                    <li class=''>

                        <a data-toggle='tab' href='#hideUsers'>
                            <i class="icon-question-sign"></i>
                            Hide
                        </a>
                    </li>
                </ul>

                <div class='tab-content'>
                    <div class="tab-pane active" id="showUsers">
                        <div class='responsive-table'>
                            <div class='scrollable-area'>
                                <table class='table table-bordered table-hover table-striped table-showuser' style='margin-bottom:0;'>
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" title="Check/Uncheck All">
                                        </th>
                                        <th>
                                            HN ID
                                        </th>
                                        <th>
                                            ชื่อ นามสกุล
                                        </th>
                                        <th>
                                            เวลา
                                        </th>
                                        <th>
                                            <div class="text-center">Action</div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="show-queue-list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="hideUsers">
                        <div class='responsive-table'>
                            <div class='scrollable-area'>
                                <table class='table table-bordered table-hover table-striped table-hideuser' style='margin-bottom:0;'>
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" title="Check/Uncheck All">
                                        </th>
                                        <th>
                                            HN ID
                                        </th>
                                        <th>
                                            ชื่อ นามสกุล
                                        </th>
                                        <th>
                                            เวลา
                                        </th>
                                        <th>
                                            <div class="text-center">Action</div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="hide-queue-list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END HIDE USER -->
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="template_tr">
<tr class="que-tr"
    vn_id=""
    hn_id=""
    dep_id=""
    datetime=""
    >

    <td><input type="checkbox" name="id[]"></td>
    <td class="hn_id"></td>
    <td class="name"></td>
    <td class="time"></td>
    <td>
        <div class='text-center'>
            <a class='btn btn-success call-btn' href="">
                <i class='icon-bullhorn'></i>
                Call
            </a>
            <a class='btn skip-btn' href=''>
                <i class='icon-mail-forward'></i>
                Skip
            </a>
            <a class='btn btn-danger hide-btn' href=''>
                <i class='icon-remove'></i>
                <span>Hide</span>
            </a>
            <input type="text" placeholder="Remark" class="remark-input" value="">
            <a class='btn btn-info remark-btn' href=''>
                <i class='icon-info'></i>
                Save
            </a>
        </div>
    </td>
</tr>
</script>
<script type="text/javascript">
$(function(){
    var conn;
    function clickSkip(e){
        e.preventDefault();
        var btn = $(this);
        //if(btn.prop('disabled')) return;
        //if(!window.confirm('Skip?')) return;

        //btn.prop('disabled', true);

        var tr = btn.closest('tr.que-tr');
        var vn_id = tr.attr('vn_id');

        var param = { vn_id: vn_id };
        conn.send(JSON.stringify({ action: 'skip', param: param }));
    }

    function skip(tr){
        $(tr).fadeOut(function(e){
            $(this).remove();
        });
    }

    function clickRemark(e){
        e.preventDefault();
        var btn = $(this);
        if(btn.prop('disabled')) return;
        //if(!window.confirm('Remark?')) return;

        var tr = btn.closest('tr.que-tr');
        var vn_id = tr.attr('vn_id');
        var input = $('.remark-input', tr);
        var remarkString = input.val();

        var param = { vn_id: vn_id, remark: remarkString };
        conn.send(JSON.stringify({ action: 'remark', param: param }));
    }

    function keyRemark(e){
        if(e.which==13){
            var tr = $(this).closest('tr.que-tr');
            $('.remark-btn', tr).click();
        }
    }

    function remark(tr, text){
        if(text != "") {
            tr.addClass('red-background-remark');
        }
        else {
            tr.removeClass('red-background-remark');
        }
        $('.remark-input', tr).val(text);
    }

    function clickHide(e){
        e.preventDefault();
        var btn = $(this);
        if(btn.prop('disabled')) return;

        var tr = btn.closest('tr.que-tr');
        var vn_id = tr.attr('vn_id');

        var isHide = $(this).closest('.table-hideuser').size()!=0;

        var param = { vn_id: vn_id, hide: !isHide };
        conn.send(JSON.stringify({ action: 'hide', param: param }));
    }

    function hide(tr, hide){
        var vn_id = tr.attr('vn_id');
        var text = 'Hide';
        var table = $('.show-queue-list');
        if(hide) {
            table = $('.hide-queue-list');
            text = 'Show';
        }

        table.append(tr);
        $('.hide-btn span', tr).text(text).trigger('update-text');

        sortQue(table);
    }

    function hideMany(data){
        for(var i in data){
            (function(){
                var tr = $('tr[vn_id="'+data[i].vn_id+'"]');
                hide(tr, data[i].hide);
            }());
        }
    }

    var suffix = $('#suffix');
    function clickCall(e){
        e.preventDefault();

        var tr = $(this).closest('tr.que-tr');
        var vn_id = tr.attr('vn_id');

        var suffix_path, room_path, room_name;
        if(suffix.val() == 1){
            suffix_path = 'public/sounds/room_opd/r-1.wav';
            room_path = 'public/img/opd_room/1.JPG';
            room_name = 'ห้องตรวจ 1';
        }
        else if(suffix.val() == 2){
            suffix_path = 'public/sounds/room_opd/r-2.wav';
            room_path = 'public/img/opd_room/2.JPG';
            room_name = 'ห้องตรวจ 2';
        }
        else if(suffix.val() == 3){
            suffix_path = 'public/sounds/room_opd/r-3.wav';
            room_path = 'public/img/opd_room/3.JPG';
            room_name = 'ห้องตรวจ 3';
        }
        else if(suffix.val() == 4){
            suffix_path = 'public/sounds/room_opd/r-4.wav';
            room_path = 'public/img/opd_room/4.JPG';
            room_name = 'ห้องตรวจ 4';
        }
        else if(suffix.val() == 5){
            suffix_path = 'public/sounds/room_opd/r-5.wav';
            room_path = 'public/img/opd_room/5.JPG';
            room_name = 'ห้องตรวจ 5';
        }
        else if(suffix.val() == 6){
            suffix_path = 'public/sounds/room_opd/r-6.wav';
            room_path = 'public/img/opd_room/6.JPG';
            room_name = 'ห้องตรวจ 6';
        }
        else if(suffix.val() == 7){
            suffix_path = 'public/sounds/room_opd/r-7.wav';
            room_path = 'public/img/opd_room/7.JPG';
            room_name = 'ห้องตรวจ 7';
        }
        else if(suffix.val() == 8){
            suffix_path = 'public/sounds/room_opd/r-8.wav';
            room_path = 'public/img/opd_room/8.JPG';
            room_name = 'ห้องตรวจ 7';
        }

        var param = {vn_id: vn_id, room_path: room_path, suffix_path: suffix_path, room_name: room_name};
        conn.send(JSON.stringify({ action: 'call', param: param }));
    }

    function createTr(obj){
        var template = $('#template_tr').html();
        var tr = $(template);
        tr.attr('vn_id', obj.vn_id);
        tr.attr('hn_id', obj.hn_id);
        tr.attr('dep_id', obj.dep_id);

        var pt_type = obj.pt_type == ""? 'ทั่วไป': obj.pt_type;
        tr.attr('pt_type', pt_type);

        var date = obj.date.date.split(" ");
        var time = obj.time.date.split(" ");
        tr.attr('datetime', date[0]+ " " +time[1]);

        var hideText = obj.hide==true? 'Show': 'Hide';

        $('.hn_id', tr).text(obj.hn_id);
        $('.name', tr).text(obj.p_name + ' ' + obj.p_surname);
        $('.time', tr).text(time[1]);
        $('.hide-btn span', tr).text(hideText);
        //$('.remark-input', tr).val(obj.remark);

        remark(tr, obj.remark);

        $('.skip-btn', tr).click(clickSkip);
        $('.remark-btn', tr).click(clickRemark);
        $('.remark-input', tr).keypress(keyRemark);
        $('.hide-btn', tr).click(clickHide);
        $('.call-btn', tr).click(clickCall);

        return tr;
    }

    function addMany(data){
        for(var i in data){
            (function(){
                var tr = createTr(data[i]);
                var table = data[i].hide? $('.hide-queue-list'): $('.show-queue-list');
                table.append(tr);
            }());
        }
        sortQue('.show-queue-list');
        sortQue('.hide-queue-list');
    }

    function sortQue(selector){
        var table = $(selector);
        table.append($("tr", table).get().sort(function(a, b) {
            var dt1 = new Date($(a).attr("datetime"));
            var dt2 = new Date($(b).attr("datetime"));

            return dt1.getTime() - dt2.getTime();
        }));
    }

    function skConnect(){
        if(conn instanceof WebSocket){
            conn.close();
        }
        conn = new WebSocket(<?php echo json_encode(url_socket());?>);

        conn.onmessage = function(e){
            var json = JSON.parse(e.data);
            var event = json.event;
            var data = json.data;

            var vn_id = typeof data.vn_id == 'undefined'? 0: data.vn_id;

            var tr = $('tr[vn_id="'+vn_id+'"]');
            if(event=='skip'){
                skip(tr);
            }
            else if(event=='remark'){
                remark(tr, data.remark);
            }
            else if(event=='hide'){
                hide(tr, data.hide);
            }
            else if(event=='add'){
                addMany(data);
            }
            else if(event =='init'){
                addMany(data);
                fetchYellow();
                $('#select_department').change();
            }
            else if(event =='hideMany'){
                hideMany(data);
            }
            data = null;
            json = null;
        };

        conn.onerror = function(){
            setTimeout(function(){ skConnect(); }, 3000);
        };

        conn.onopen = function(){
            $('.show-queue-list tr').remove();
            $('.hide-queue-list tr').remove();
            conn.send(JSON.stringify({ action: 'init' }));
        }
    };

    skConnect();

    $('.skip-btn').click(clickSkip);
    $('.remark-btn').click(clickRemark);
    $('.remark-input').keypress(keyRemark);
    $('.hide-btn').click(clickHide);
    $('.call-btn').click(clickCall);
    $('.hide-many-form').submit(function(e){
        e.preventDefault();
        var minute = $('#hide-minute').val();
        conn.send(JSON.stringify({ action: 'hideMany', param: { minute: minute } }));
    });

    // fetch yellow function every 5 secound
    function fetchYellow(){
        $('tr.que-tr').each(function(){
            var datetime = $(this).attr('datetime');
            var date = new Date(datetime);
            var now = new Date();

            if(date.getTime() < (now.getTime()-(30*60000))){
                $(this).addClass('yellow-bg');
            }
        });
    }

    fetchYellow();
    setInterval(fetchYellow, 5000);
});
</script>