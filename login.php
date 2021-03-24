<?php
  include 'functions.php';
  if(!empty($_SESSION['login']))
    header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>LOGIN</title>
    <link href="assets/css/united-bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/signin.css" rel="stylesheet"/>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>      
  </head>

  <body>
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
      <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <a class="navbar-brand">Sistem Pendukung Keputusan (SPK) Metode Analytical Network Proccess(ANP) berbasis Web dengan PHP dan MySQL</a>
  </nav>
      <form class="form-signin" action="?act=login" method="post">        
        <h2 class="form-signin-heading">Silahkan login</h2>
        <?php if($_POST) include 'aksi.php'; ?>
        <label for="inputEmail" class="sr-only">Usernames</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="user" autofocus />
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" />        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Masuk</button>        
      </form>      
    </div>
  </body>
</html>
