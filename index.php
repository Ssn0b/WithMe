<?php
function dbopen($location) {
    $handle = new SQLite3($location);
    return $handle;
}
function dbclose($handle) {
    $handle->close();
}
function dbquery($dbhandle,$query) {
    $array['dbhandle'] = $dbhandle;
    $array['query'] = $query;
    $result = $dbhandle->query($query);
    return $result;
}
function dbarray(&$result) {
    $resx = $result->fetchArray();
    return $resx;
}

$db = dbopen('helen.db');

define("COOKIE_HELEN", "1234");
define("DOMAIN", "http://helen.8-8-8.ovh/");

function _setCookie ($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure = false, $httpOnly = false) {
	if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
		setcookie($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure, $httpOnly);
	} else {
		setcookie($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure);
	}
}

function check() {
	if (isset($_COOKIE[COOKIE_HELEN]) && $_COOKIE[COOKIE_HELEN] != "") {
		$cookieDataArr = explode(".", $_COOKIE[COOKIE_HELEN]);
		if (count($cookieDataArr) == 3) {
			list($userID, $cookieExpiration, $cookieHash) = $cookieDataArr;
			if ($cookieExpiration > time()) {
				return true;
			} else {
	        return false;
	        }	
		} else {
	        return false;
	    }
    } else {
	    return false;
	}		
}

define("COOKIE_DOMAIN", $_SERVER['SERVER_NAME']);
define("COOKIE_PATH", "/");

if (isset($_GET['exit'])) {
	_setCookie(COOKIE_HELEN, "", time() - 1209600, COOKIE_PATH, COOKIE_DOMAIN, false, true);
	echo "<script type='text/javascript'>document.location.href='/'</script>";
}

require_once "header.php";

if (isset($_GET['action']) && !empty($_GET['action'])) {
	
	if ($_GET['action'] == "reg") {
	if (!check()) { 
		if (isset($_POST['submit'])) {
			if (isset($_POST['login']) && !empty($_POST['login'])) {
				if (isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['password2']) && !empty($_POST['password2'])) {
					if ($_POST['password'] == $_POST['password2']) {
						$result = dbquery($db, "SELECT user_id FROM users WHERE user_email='".$_POST['login']."'");
						if (empty(dbarray($result))) {
							$result2 = dbquery($db, "INSERT INTO users (user_email, user_password, user_date) VALUES ('".$_POST['login']."', '".md5($_POST['password'])."', ".time().")");
							echo "<div class='alert alert-success' role='alert'>Ви успішно зареєструвались....</div>";
						} else {
							echo "<div class='alert alert-danger' role='alert'>Такий користувач, вже зареєстрований.</div>";
						}
					} else {
						echo "<div class='alert alert-danger' role='alert'>Паролі різні, потрібно одинакові.</div>";
					}
				} else {
					echo "<div class='alert alert-danger' role='alert'>Введіть пароль.</div>";
				}
			} else {
				echo "<div class='alert alert-danger' role='alert'>Введіть логін.</div>";
			}
		}
		echo "<form class='uk-form' name='reg' id='reg' method='post' action='/?action=reg' enctype='multipart/form-data'>";
		echo "<div class='form-mine'>
		<form>
		  <div class='mb-3'>
			<label for='login' class='form-label'>Ваша пошта</label>
			<input type='email' class='form-control' name='login' aria-describedby='registration'>
		  </div>
		  <div class='mb-3'>
			<label for='password' class='form-label'>Пароль</label>
			<input type='password' class='form-control' name='password'>
		  </div>
		  <div class='mb-3'>
			<label for='password2' class='form-label'>Повторіть пароль</label>
			<input type='password' class='form-control' name='password2'>
		  </div>
		  <button type='submit' name='submit' class='btn btn-primary'>Зареєстуватись</button>
		</form>
		</div>";
		echo "</form>";
	} else {
		echo "<script type='text/javascript'>document.location.href='/'</script>";
	}
	} elseif ($_GET['action'] == "add") {
		
		echo "<div class='form-mine'>
		<h3 class='mb-3'>Додавання події</h3>
		<form>
		  <div class='mb-3'>
			<label for='name' class='form-label'>Назва події</label>
			<input type='text' class='form-control' id='name'>
		  </div>
		  <div class='mb-3'>
			<label for='short' class='form-label'>Опис події</label>
			<textarea type='text' class='form-control' id='short' rows='4'></textarea>
		  </div>
		  <div class='mb-3'>
			<label for='date' class='form-label'>Дата події</label>
			<input type='text' class='form-control' id='date'>
		  </div>
		  <button type='submit' name='submit' class='btn btn-primary'>Додати подію</button>
		</form>
		</div>";	
	
	} else {
		
		if (!check()) { 
			if (isset($_POST['submit'])) {
				if (isset($_POST['login']) && !empty($_POST['login'])) {
					if (isset($_POST['password']) && !empty($_POST['password'])) {
						
						//echo md5($_POST['password']);
						//echo $_POST['login'];
						$result = dbquery($db, "SELECT user_id FROM users WHERE user_password='".md5($_POST['password'])."' AND user_email='".$_POST['login']."'");
						if (!empty(dbarray($result))) {
						$cookieExpiration = time() + 172800; // 48 hours
						if (isset($_POST['rememberme'])) $cookieExpiration = time() + 1728000; // 480 hours
						$cookieContent = "1.".$cookieExpiration.".".sha1(rand(1,2000));
						_setCookie(COOKIE_HELEN, $cookieContent, $cookieExpiration, COOKIE_PATH, COOKIE_DOMAIN, false, true);
						echo "<script type='text/javascript'>document.location.href='/'</script>";
						} else {
							echo "<div class='alert alert-danger' role='alert'>Неправильні дані входу.</div>";
						}
					} else {
						echo "<div class='alert alert-danger' role='alert'>Введіть пароль.</div>";
					}
				} else {
					echo "<div class='alert alert-danger' role='alert'>Введіть пошту для авторизації.</div>";
				}
			}
		echo "<form class='uk-form' name='reg' id='reg' method='post' action='/?action=login' enctype='multipart/form-data'>";
		echo "<div class='form-mine'>
			  <div class='mb-3'>
				<label for='login' class='form-label'>Ваша пошта</label>
				<input type='email' class='form-control' name='login' aria-describedby='login'>
			  </div>
			  <div class='mb-3'>
				<label for='password' class='form-label'>Пароль</label>
				<input type='password' class='form-control' name='password'>
			  </div>
			  <div class='mb-3 form-check'>
				<input type='checkbox' class='form-check-input' id='exampleCheck1'>
				<label class='form-check-label' name'rememberme' for='exampleCheck1'>Запам'ятати мене </label>
			  </div>
			  <button type='submit' name='submit' class='btn btn-primary'>Увійти</button>
		</div></form>";
		} else {
			echo "<script type='text/javascript'>document.location.href='/'</script>";
		}
	}
	
} else {

$mon_name = array("Січень","Лютий","Березень","Квітень","Травень","Червень", "Липень","Серпень","Вересень","Жовтень","Листопад","Грудень");
$nod = array (31,28,31,30,31,30,31,31,30,31,30,31);

if (!isset($_GET['month']) &&!isset($_GET['year'])) {
	$ac_month = date("n");
	$ac_year = date("Y");
	$ac_j_dom = date("j");
	$ac_j_dow = date("w");
} else {
	$ac_month = $_GET['month'];
	$ac_year = $_GET['year'];
	if ($ac_year<1980) $ac_year = 1980;
	if ($ac_year>2030) $ac_year = 2030;
	if ($ac_month != date("n") or $ac_year != date("Y")) {
		$ac_j_dom = 1;
		$ac_j_dow = date("w",mktime(0,0,0,$ac_month,1,$ac_year));
    } else {
		$ac_j_dom = date("j");
		$ac_j_dow = date("w");
    }
}
if ($ac_year%4==0) {$nod[1]=29;}
$temp_month = $ac_month + 1;
if ($temp_month!=13) {
	$ac_month_next = "$ac_year&month=$temp_month";
} else {
	$temp_year = $ac_year + 1;
	$ac_month_next = "$temp_year&month=1";
}
$temp_month = $ac_month - 1;
if ($temp_month!=0) {
	$ac_month_prev = "$ac_year&month=$temp_month";
} else {
	$temp_year = $ac_year - 1;
	$ac_month_prev = "$temp_year&month=12";
}
$temp_year = $ac_year + 1;
$ac_year_next = "$temp_year&month=$ac_month";
$temp_year = $ac_year - 1;
$ac_year_prev = "$temp_year&month=$ac_month";
$ac_mon=$mon_name[$ac_month-1];
if ($ac_j_dow == 0) $ac_j_dow = 7;
$ac_1_dow = $ac_j_dow - ($ac_j_dom%7 - 1);
if ($ac_1_dow < 1) $ac_1_dow+=7;
if ($ac_1_dow > 7) $ac_1_dow-=7;
$ac_nod = $nod[$ac_month-1];
$ac_now=5;
if ($ac_1_dow-1+$ac_nod<29) { 
	$ac_now=4;
} else if ($ac_1_dow-1+$ac_nod>35) {
	$ac_now=6;
}
if ($ac_month != date("n") or $ac_year != date("Y")) $ac_j_dom = -10;
echo "<div class='form-mine'><div class='alert alert-info' role='alert'>".$ac_mon." ".$ac_year."</div>";
$ferst_date = date("U",mktime(0,0,0,$ac_month,1,$ac_year)); 
$last_date = date("U",mktime(24,0,0,$ac_month,$ac_nod,$ac_year)); 
$number_day_date = date("d",time()); 
echo "<table width='100%' border=0 cellspacing=1 cellpadding=1 >
<tr><td colspan='7' bgcolor='black'></td></tr>
<tr class='main-body' align=center>
<td ><b>Пн</b></td>
<td ><b>Вт</b></td>
<td ><b>Ср</b></td>
<td ><b>Чт</b></td>
<td ><b>Пт</b></td>
<td ><b>Сб</b></td>
<td ><b>Вс</b></td>
<Tr><Td colspan='7' bgcolor='black'></Td></Tr>";
for ($i=0;$i<$ac_now*7;$i++)
{
if ($i%7==0) {echo "<tr align=center class='main-body'>";}
if($i-$ac_1_dow+2==$ac_j_dom) {echo "<td bgcolor='silver'><font color='#000000'>";}
elseif ((($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2))&&($i==41||$i==34)){echo "<td><font color='#000000'>";}
elseif($i==6||$i==13||$i==20||$i==27||$i==34||$i==41 ) {echo "<td ><font color='red'>";}
elseif ($i-$ac_1_dow+2!=$ac_j_dom) {echo "<td><font color='000000'>";}
if (($ac_month == date('n')) && ($ac_year == date('Y'))) {
    if($i-$ac_1_dow+2 <= date('j')) {
    if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo " ";}
    else { $chis = $i-$ac_1_dow+2;
    echo "<a href='".DOMAIN."?calendar&year=".$ac_year."&month=".$ac_month."&day=".$chis."' title='Події за за ".($i-$ac_1_dow+2)." $ac_mon $ac_year'><b>";echo $i-$ac_1_dow+2; echo "</b></a>\t"; }
    } else{ if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo "";} else {echo $i-$ac_1_dow+2;}}
} else {
    if ($ac_year<=2010) {
	if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo "";} else {echo $i-$ac_1_dow+2;}
	} elseif (($ac_month>=date('n')) && ($ac_year>=date('Y'))) {
	if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo "";} else {echo $i-$ac_1_dow+2;}
	} else {
	if(($i-$ac_1_dow+2 <= 31)) {
    if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo " ";}
    else { $chis = $i-$ac_1_dow+2;
    echo "<a href='".DOMAIN."?calendar&year=".$ac_year."&month=".$ac_month."&day=".$chis."' title='Події за ".($i-$ac_1_dow+2)." $ac_mon $ac_year'><b>";echo $i-$ac_1_dow+2; echo "</b></a>\t"; }
    } else{ if (($i<$ac_1_dow-1)||($i>$ac_nod+$ac_1_dow-2)) {echo "";} else {echo $i-$ac_1_dow+2;}}
	}
}		
echo "</font></td>";
}
echo "</tr>
<tr><td colspan='7' bgcolor='black'></td></tr>
<tr class='scapmain'>
<td colspan=7 align=center >
<a href='".DOMAIN."?calendar&year=$ac_year_prev' title='Рік назад'><<&nbsp;</a>
<a href='".DOMAIN."?calendar&year=$ac_month_prev' title='Місяць назад'><&nbsp;</a>
<a href='".DOMAIN."?calendar&year=".date("Y")."&month=".date("n")."' title='Цей місяць'>Цей місяць</a>
<a href='".DOMAIN."?calendar&year=$ac_month_next' title='Місяць вперед'>&nbsp;></a>
<a href='".DOMAIN."?calendar&year=$ac_year_next' title='Рік назад'>&nbsp;>> </a></td>";
echo "</tr>
<tr><td colspan='7' bgcolor='black'></td></tr>
</table></div>";

	echo "<div class='form-mine'>
<h3 class='mb-3'>ТУТ НАЗВА ПОДІЇ</h3>
<p class='mb-3 text-secondary'>04.12.2021</p>
<p class='lead mb-4'>Тут опис події.</p>
</div>";

}

require_once "footer.php"
?>
