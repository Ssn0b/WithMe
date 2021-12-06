<?php
echo "<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<title>Olenka</title>
	<link rel='stylesheet' href='/css/jquery-ui.css'>
	<link rel='stylesheet' href='/fc/fullcalendar.css'>
	<link rel='stylesheet' href='/fc/fullcalendar.print.css' media='print'>
	<link rel='stylesheet' href='/css/style.css'>
	<link rel='stylesheet' href='/css/bootstrap.min.css'>
</head>
<body>
<nav class='navbar navbar-expand-lg navbar-light bg-light mb-4'>
  <div class='container'>
    <a class='navbar-brand'>WithMe</a>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <li class='nav-item'>
          <a class='nav-link active' aria-current='page' href='/'>Головна</a>
        </li>
		
		
		
		<li class='nav-item'>
          <a class='nav-link active' aria-current='page' href='/pro-proekt.html'>Про проект</a>
        </li>
		<li class='nav-item'>
          <a class='nav-link active' aria-current='page' href='/rules.html'>Правила</a>
        </li>
		<li class='nav-item'>
          <a class='nav-link active' aria-current='page' href='/index.php?do=feedback'>Зворотній зв'язок</a>
        </li>
		
		
		
      </ul>
	  <ul class='navbar-nav'>";
	  if (check()) { 
        echo "<li class='nav-item'>
          <a class='nav-link' href='/?exit=1'>Вихід</a>
        </li>";	  
	  } else {
        echo "<li class='nav-item'>
          <a class='nav-link' href='/?action=login'>Авторизація</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='/?action=reg'>Реєстрація</a>
        </li>";
	  }
	echo "</ul>
    </div>
  </div>
</nav>";
?>