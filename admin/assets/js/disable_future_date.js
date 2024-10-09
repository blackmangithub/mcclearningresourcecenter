var todayDate = new Date();
var month = todayDate.getMonth() + 1;
var year = todayDate.getUTCFullYear(); // current year
var tdate = todayDate.getDate(); // current date 
if (month < 10) {
     month = "0" + month //'0' + 4 = 04
}
if (tdate < 10) {
     tdate = "0" + tdate;
}
// var maxDate = month + "-" + tdate + "-" + year;
var maxDate = year + "-" + month + "-" + tdate;
document.getElementById("disable_date").setAttribute("max", maxDate);
document.getElementById("disable_date2").setAttribute("max", maxDate);
// alert(maxDate);  