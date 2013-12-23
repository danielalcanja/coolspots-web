<?php
//use Symfony\Component\ClassLoader\ApcClassLoader;
//use Symfony\Component\HttpFoundation\Request;
//
//$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
//
//// Use APC for autoloading to improve performance.
//// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
//// with other applications also using APC.
///*
//$loader = new ApcClassLoader('sf2', $loader);
//$loader->register(true);
//*/
//
//require_once __DIR__.'/../app/AppKernel.php';
////require_once __DIR__.'/../app/AppCache.php';
//
//$kernel = new AppKernel('prod', false);
//$kernel->loadClassCache();
////$kernel = new AppCache($kernel);
//Request::enableHttpMethodParameterOverride();
//$request = Request::createFromGlobals();
//$response = $kernel->handle($request);
//$response->send();
//$kernel->terminate($request, $response);

// PARA REMOVER A SENHA, REMOVA DAQUI ....
function Autorizacao()
{
	header('WWW-Authenticate: Basic realm="CoolSpots!"');
	header('HTTP/1.0 401 Unauthorized');
	echo 'Acesso não autorizado';
	exit();
}

if($_SERVER['SERVER_NAME'] != 'localhost') {
	if(!strstr($_SERVER['SERVER_NAME'], 'api')) {
		if(!isset($_SERVER['PHP_AUTH_USER'])) {
			Autorizacao();
		} else {
			if($_SERVER['PHP_AUTH_USER'] != 'coolspots' || $_SERVER['PHP_AUTH_PW'] != 'c00lsp0ts!') {
			 Autorizacao();
			}
		}
	}
}

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);


$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
