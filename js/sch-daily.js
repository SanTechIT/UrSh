function name(){
    var daySchedule = "a"
    switch (daySchedule){
        case "a":
            break;

    }
}
function isWeekday(){
    var d = new Date();
    var n = d.getDay();
    return (n < 5);
}
function isHoliday(){
    var d = new Date();
    var n = d.getDate();
    return n;
}