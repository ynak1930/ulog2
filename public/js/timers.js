
function timer(timer){
    //var start_at = <?php echo json_encode($task->start_at); ?>;
    //var timer = <?php echo json_encode($task->timer); ?>;
    //var timer = timer * 1000;
    //var timer = 1000;
    //var from = new Date(start_at);
    //var from = new Date("2016/3/1 23:44:59");
    var now = new Date();
        
    // 経過時間をミリ秒で取得
    //var ms = new Date(now.getTime() - from.getTime()+timer-60*60*9*1000);
    // ミリ秒を日付に変換(端数切捨て)
    //var days = Math.floor(ms / (1000*60*60*24));
                                    
    //document.getElementById("time").innerHTML = days.toLocaleTimeString();
    //document.getElementById("time").innerHTML = ms.toLocaleTimeString();
//    timer = timer-1;
    document.getElementById("time").innerHTML = timer;
    redraw_timer(timer);

function time(){

}

}


function redraw_timer(timer){



}
setInterval('time()',1000);