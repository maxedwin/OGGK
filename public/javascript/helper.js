function pascua($annio) {
	if($annio>1583 && $annio<1699) { $M=22; $N=2; } 
	else if ($annio>1700 && $annio<1799) { $M=23; $N=3; }
	else if ($annio>1800 && $annio<1899) { $M=23; $N=4; }
	else if ($annio>1900 && $annio<2099) { $M=24; $N=5; }
	else if ($annio>2100 && $annio<2199) { $M=24; $N=6; }
	else if ($annio>2200 && $annio<2299) { $M=25; $N=0; } 
	$a = $annio % 19;
	$b = $annio % 4;
	$c = $annio % 7;
	$d = ((19*$a) + $M) % 30;
	$e = ((2*$b) + (4*$c) + (6*$d) + $N) % 7;
	$f = $d + $e;
	if ($f < 10) { $dia = $f + 22; $mes = 3; } 
	else  {  $dia = $f - 9;  $mes = 4; }
	if($dia==26 && $mes==4){ $dia = 19; }
	if($dia==25 && $mes==4 && $d==28 && $e==6 && $a>10) { $dia = 18; }
	$pascua = new Date($annio,$mes-1,$dia);
	return $pascua;
};

// date: yyyy-mm-dd
function isHolidayDate($date) {
	var dayDate = new Date($date+' 00:00:00');
	var holidayDays = {'01-01':1,'05-01':1,'06-29':1,'07-28':1,'07-29':1,'08-30':1,'10-08':1,'11-01':1,'12-08':1,'12-25':1};
	var domPascua = pascua(parseInt($date.slice(0,4)));
	domPascua.setDate(domPascua.getDate()-2);
	var viePascua = getDateFormat(domPascua);
	domPascua.setDate(domPascua.getDate()-1);
	var juePascua = getDateFormat(domPascua);
	var weekday = dayDate.toLocaleDateString("es-ES", { weekday: 'long' });
	//console.log(weekday);
	//console.log(juePascua);
	//console.log(viePascua);
	return (weekday == 'domingo' || $date.slice(5,10) in holidayDays || $date == viePascua || $date == juePascua);
}

function getTodayFormat() {
    var today = new Date();
    return getDateFormat(today);
}

function getDateFormat(date) {
	month = '' + (date.getMonth() + 1),
    day = '' + date.getDate(),
    year = date.getFullYear();
    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
    return [year, month, day].join('-');
}