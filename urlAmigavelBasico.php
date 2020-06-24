<?php

// URL amigáveis para iniciantes 
//Este arquivo deve ser o index.php e estar na raiz /

if ($_GET) 
{
	$url = explode("/",$_REQUEST['url']);
	require_once 'views/'.$url[0].'.php';
}
else
{
	require_once 'views/telaPrincipal.php';
}


// No arquivo .htaccess deve conter os comandos abaixo 

// RewriteEngine On
// RewriteCond %{REQUEST_FILENAME} !-f
// RewriteCond %{REQUEST_FILENAME} !-d
// RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]


?>