<?php

include'functions.php';
if(empty($_SESSION['login']))
    header("location:login.php"); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Sistem Pendukung Keputusan (SPK) Metode Analytical Network Proccess(ANP) berbasis web dengan PHP dan MySQL. Studi kasus: LP2M UM-PALANGKARAYA"/>
    <meta name="author" content="PKDI-2020"/>
    <link rel="icon" href="assets/images/favicon.ico"/>
    <link rel="canonical" href="https://YH.ac.id" />

    <title>PEMILIHAN CALON KEPALA DESA</title>
    <link href="assets/css/united-bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/general.css" rel="stylesheet"/>
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
          <a class="navbar-brand" href="?">PEMILIHAN CALON KEPALA DESA <?=get_tahun_aktif()?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-calendar"></span> Periode<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">                 
                <li><a href="?m=atur_periode"><span class="glyphicon glyphicon-wrench"></span> Atur Periode</a></li>
                <li><a href="?m=hasil"><span class="glyphicon glyphicon-th-large"></span>Hasil Pemilihan</a></li>    
               </ul>
            </li>              
            <li><a href="?m=alternatif"><span class="glyphicon glyphicon-user"></span> Alternatif</a></li>
            <li><a href="?m=kriteria"><span class="glyphicon glyphicon-th-large"></span> Kriteria</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Nilai Bobot<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">                 
                <li><a href="?m=rel_kriteria"><span class="glyphicon glyphicon-th-large"></span> Nilai bobot kriteria</a></li>
                <li><a href="?m=rel_alternatif"><span class="glyphicon glyphicon-user"></span> Nilai bobot alternatif</a></li>    
               </ul>
            </li>      
            <li><a href="?m=hitung"><span class="glyphicon glyphicon-calendar"></span> Perhitungan</a></li>
            <li><a href="?m=password"><span class="glyphicon glyphicon-lock"></span> Password</a></li>
            <li><a href="aksi.php?act=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>                   
          </ul>          
          <div class="navbar-text"></div>
        </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container hentry">
    <?php
        
        if(file_exists($mod.'.php')){
            include $mod.'.php';
        }elseif($mod=='atur_periode_status_ok'){
            include 'aksi.php';
        }else{
            include 'home.php';
        }
    ?>
    </div>
    <footer class="footer bg-primary">
      <div class="container">
        <p>Copyright &copy; <?=date('Y')?> PEMILIHAN CALON KADES <em class="pull-right">Updated: 24 FEBRUARI 2021</em></p>
      </div>
    </footer>
</body>
</html>