<style type="text/css">
#slide-wrap {
    -webkit-transition: 500ms all;
    -moz-transition: 500ms all;
    transition: 500ms all;
}

.show-block {
    border: 0;
    margin: 0;
    display: block;
    float: left;

    height: 100%;
    width: 400px;
}
</style>
<div id="slide-wrap" data-section="0" style="height: 100%; width: 3000px;">
    <div class="show-block" pt_type="ทั่วไป">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> ทั่วไป
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="FollowUp">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> FollowUp
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="Chronic">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> Chronic
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="ตรวจสุขภาพ">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> ตรวจสุขภาพ
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="เฉพาะทาง">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> เฉพาะทาง
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="เด็ก">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> เด็ก
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
    <div class="show-block" pt_type="อื่นๆ">
        <div class="row-fluid dep-ctx">
            <div class="span12 box">
                <div class="box-header red-background">
                    <div class="text-right title" style="float: right;">
                        <i class="icon-list"></i> อื่นๆ
                    </div>
                </div>
                <div class="box-content" style="padding:0px;">

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    var sw = $('#slide-wrap');
    function slide(){
        var section = sw.data('section');
        section++;
        if(section >= 7){
            section = 0;
        }

        var left = -(section*400);
        sw.css('margin-left', left+'px');
        sw.data('section', section);
    }

    setInterval(slide, 5000);
});
</script>

<script type="text/javascript">
    $(function(){
        function fetchYellow(){
            $('.que-ctx').each(function(index, el){
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

        var callStack = {
            stack: [],
            calling: false,
            push: function(data){
                callStack.stack.push(data);
            },
            call: function(){
                if(callStack.stack.length == 0){
                    callStack.calling = false;
                    return;
                }
                callStack.calling = true;

                var data = callStack.stack.shift();

                var params = [
                    'height='+screen.height,
                    'width='+screen.width,
                    'left=0',
                    'top=0'
                    //'fullscreen=yes' // only works in IE, but here for completeness
                ].join(',');

                var w = window.open('index.php?page=user/call_dru&id='+data.id, '', params);
                w.onload = function(){
                    w.onunload = function(){
                        callStack.call();
                    };
                }
            }
        };

        window.callstack = callStack;

        var conn;
        function skConnect(){
            if(conn instanceof WebSocket){
                conn.close();
            }
            conn = new WebSocket(<?php echo json_encode(url_socket());?>);

            conn.onmessage = function(e){
                var json = JSON.parse(e.data);
                var event = json.event;
                var data = json.data;

                if(event=='call'){
                    callStack.push(data);
                    if(!callStack.calling){
                        callStack.call();
                    }
                }
                else if(event=='show/update'){
                    console.log(data);
                    for(var i in data){
                        $('.show-block[pt_type="'+data[i].name+'"] .box-content').html(data[i].html);
                    }
                    fetchYellow();
                }
            };

            conn.onerror = function(){
                setTimeout(function(){ skConnect(); }, 3000);
            };

            conn.onopen = function(){
                conn.send(JSON.stringify({ action: 'show/init' }));
            }
        };

        skConnect();
    });
</script>