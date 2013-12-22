<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
  'view' => '\Slim\LayoutView',
  'layout' => 'layouts/main.php',
  'cookies.encrypt' => true,
  'cookies.secret_key' => 'LKJLSD4343%$$#$KSDJH'
));

$view = $app->view();
$view->setTemplatesDirectory('./views');

$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'myappsecret')));

$authenticate = function ($app) {
  return function () use ($app) {
    if (!isset($_SESSION['user'])) {
      $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
      $app->flash('error', 'Login required');
      $app->redirect('/login');
    }
  };
};

$app->hook('slim.before.dispatch', function() use ($app) { 
  $user = null;
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }
  $app->view()->setData('user', $user);
});

$app->get('/', $authenticate($app), function($path = "") use ($app) {
  $app->redirect('/files');
});

$app->get('/download(/)(:path+)', $authenticate($app), function($path = "") use ($app) {
  require 'conf/conf.php';
  $dir = $conf->picture_dir;
  if(!empty($path)) {
    $path = '/'.implode($path,'/');
    $parent = dirname($path);
  } else {
    $parent = '';
  }
  $file = $dir.$path;
  if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
  }
});

$app->get('/files(/)(:path+)', $authenticate($app), function($path = "") use ($app) {
  require 'conf/conf.php';
  $dir = $conf->picture_dir;
  if(!empty($path)) {
    $path = '/'.implode($path,'/');
    $parent = dirname($path);
  } else {
    $parent = '';
  }
  $files = scandir($dir.$path);

  $app->view()->setData('files', $files);
  $app->view()->setData('path', $path);
  $app->view()->setData('parent', $parent);
  $app->view()->setData('dir', $dir);

  $app->render('home.php', array('files', $files));
});

$app->get('/pictures', $authenticate($app), function() use ($app) {
  require 'conf/conf.php';
  $dir = $conf->picture_dir;
  $files = scandir($dir, 1);
  $app->render('home.php');
});

$app->get('/login', function() use ($app) {
  $app->render('login.php');
});

$app->get('/logout', function() use ($app) {
  unset($_SESSION['user']);
  $app->redirect('/');
});

$app->post('/auth', function() use ($app) {
  require 'conf/conf.php';
  if( $conf->username == $app->request->params('username')
    && $conf->password == $app->request->params('password')) {
      $_SESSION['user'] = $app->request->params('username');
      $app->redirect('/');
    } else {
      $app->render('login.php');
    }
});

$app->run();
