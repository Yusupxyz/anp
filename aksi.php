<?php
error_reporting(0);
ini_set('display_errors',0);
require_once'functions.php';
    
    /** LOGIN **/
    if($act=='login'){
        $user = esc_field($_POST['user']);
        $pass = esc_field($_POST['pass']);
        
        $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$user' AND pass='$pass'");
        if($row){
            $_SESSION[login] = $row->user;
            redirect_js("index.php");
        } else{
            print_msg("Salah kombinasi username dan password.");
        } 
    }else if ($mod=='password'){
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        $pass3 = $_POST['pass3'];
        
        $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$_SESSION[login]' AND pass='$pass1'");        
        
        if($pass1=='' || $pass2=='' || $pass3=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif(!$row)
            print_msg('Password lama salah.');
        elseif($pass2!=$pass3)
            print_msg('Password baru dan konfirmasi password baru tidak sama.');
        else{        
            $db->query("UPDATE tb_admin SET pass='$pass2' WHERE user='$_SESSION[login]'");                    
            print_msg('Password berhasil diubah.', 'success');
        }
    }elseif($act=='logout'){
        unset($_SESSION[login]);
        header("location:login.php");
    } 
    /** ALTERNATIF **/
    elseif($mod=='alternatif_tambah'){
        $kode_alternatif = $_POST['kode_alternatif'];
        $nama_alternatif = $_POST['nama_alternatif'];
        if($kode_alternatif=='' || $nama_alternatif=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif'"))
            print_msg("Kode sudah ada!");
        else{
            $id_periode = $db->get_results("SELECT id FROM tb_periode where status='1'")[0]->id;

            $db->query("INSERT INTO tb_alternatif (kode_alternatif, nama_alternatif,id_periode) VALUES ('$kode_alternatif', '$nama_alternatif','$id_periode')");
            
            $rows = $db->get_results("SELECT kode_kriteria FROM tb_kriteria");
            foreach($rows as $row){
                $db->query("INSERT INTO tb_rel_alternatif(kode1, kode2, kode_kriteria, nilai) SELECT '$kode_alternatif', kode_alternatif, '$row->kode_kriteria', 1 FROM tb_alternatif");    
                $db->query("INSERT INTO tb_rel_alternatif(kode1, kode2, kode_kriteria, nilai) SELECT kode_alternatif, '$kode_alternatif', '$row->kode_kriteria', 1 FROM tb_alternatif WHERE kode_alternatif<>'$kode_alternatif'");
            }        
            
            $rows = $db->get_results("SELECT kode_kriteria FROM tb_kriteria");
            foreach($rows as $row){
                $db->query("INSERT INTO tb_rel_kriteria(kode1, kode2, kode_alternatif, nilai) 
                    SELECT '$row->kode_kriteria', kode_kriteria, '$kode_alternatif', 1 FROM tb_kriteria"); 
            }           
            redirect_js("index.php?m=alternatif");
        }
    } elseif ($mod=='alternatif_ubah'){
        $kode_alternatif = $_POST['kode_alternatif'];
        $nama_alternatif = $_POST['nama_alternatif'];
        if($kode_alternatif=='' || $nama_alternatif=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif' AND kode_alternatif<>'$_GET[ID]'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("UPDATE tb_alternatif SET kode_alternatif='$kode_alternatif', nama_alternatif='$nama_alternatif' WHERE kode_alternatif='$_GET[ID]'");
            redirect_js("index.php?m=alternatif");
        }
    } elseif ($act=='alternatif_hapus'){
        $db->query("DELETE FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_alternatif WHERE kode1='$_GET[ID]' OR kode2='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_kriteria WHERE kode_alternatif='$_GET[ID]'");
        $db->query("DELETE FROM tb_alt_krit WHERE kode_alternatif='$_GET[ID]'");
        $db->query("DELETE FROM tb_krit_alt WHERE kode_alternatif='$_GET[ID]'");
        header("location:index.php?m=alternatif");
    } 
    
    /** KRITERIA */    
    if($mod=='kriteria_tambah'){
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        if($kode=='' || $nama=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("INSERT INTO tb_kriteria (kode_kriteria, nama_kriteria) VALUES ('$kode', '$nama')");
            $rows = $db->get_results("SELECT kode_alternatif FROM tb_alternatif");
            foreach($rows as $row){
                $db->query("INSERT INTO tb_rel_kriteria(kode_alternatif, kode1, kode2, nilai) 
                    SELECT '$row->kode_alternatif', '$kode', kode_kriteria, 1 FROM tb_kriteria");
                $db->query("INSERT INTO tb_rel_kriteria(kode_alternatif, kode1, kode2, nilai) 
                    SELECT '$row->kode_alternatif', kode_kriteria, '$kode', 1 FROM tb_kriteria WHERE kode_kriteria<>'$kode'"); 
            }
                    
            $rows = $db->get_results("SELECT kode_alternatif FROM tb_alternatif");
            foreach($rows as $row){
                $db->query("INSERT INTO tb_rel_alternatif(kode1, kode2, kode_kriteria, nilai) 
                    SELECT '$row->kode_alternatif', kode_alternatif, '$kode', 1 FROM tb_alternatif"); 
            }            
            redirect_js("index.php?m=kriteria");
        }    
    } else if($mod=='kriteria_ubah'){
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        if($kode=='' || $nama=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode' AND kode_kriteria<>'$_GET[ID]'"))
            print_msg("Kode sudah ada!");
        else{
            $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode', nama_kriteria='$nama' WHERE kode_kriteria='$_GET[ID]'");
            redirect_js("index.php?m=kriteria");
        }    
    } else if ($act=='kriteria_hapus'){
        $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_kriteria WHERE kode1='$_GET[ID]' OR kode2='$_GET[ID]'");
        $db->query("DELETE FROM tb_rel_alternatif WHERE kode_kriteria='$_GET[ID]'");
        $db->query("DELETE FROM tb_krit_alt WHERE kode_kriteria='$_GET[ID]'");
        $db->query("DELETE FROM tb_alt_krit WHERE kode_kriteria='$_GET[ID]'");
        header("location:index.php?m=kriteria");
    } 
    
    /** RELASI ALTERNATIF */ 
    else if ($mod=='rel_alternatif'){
        if($_GET['kode_kriteria']==''){
            print_msg('Pilih kriteria terlebih dulu.');
        }elseif($_POST[kode1]==$_POST[kode2] && $_POST[nilai]<>1){
            print_msg('Alternatif yang sama harus bernilai 1.');
        }else{
            $db->query("UPDATE tb_rel_alternatif SET nilai='$_POST[nilai]' WHERE kode1='$_POST[kode1]' AND kode2='$_POST[kode2]' AND kode_kriteria='$_GET[kode_kriteria]'");
            $db->query("UPDATE tb_rel_alternatif SET nilai=1/'$_POST[nilai]' WHERE kode1='$_POST[kode2]' AND kode2='$_POST[kode1]' AND kode_kriteria='$_GET[kode_kriteria]'");
            print_msg('Data berhasil diubah.', 'success');
        }
    }
    
    /** RELASI KRITERIA */
    else if ($mod=='rel_kriteria'){
        if($_GET['kode_alternatif']==''){
            print_msg('Pilih alternatif terlebih dulu.');
        }elseif($_POST[kode1]==$_POST[kode2] && $_POST[nilai]<>1){
            print_msg('Kriteria yang sama harus bernilai 1.');
        }else{
            $db->query("UPDATE tb_rel_kriteria SET nilai='$_POST[nilai]' WHERE kode1='$_POST[kode1]' AND kode2='$_POST[kode2]' AND kode_alternatif='$_GET[kode_alternatif]'");
            $db->query("UPDATE tb_rel_kriteria SET nilai=1/'$_POST[nilai]' WHERE kode1='$_POST[kode2]' AND kode2='$_POST[kode1]' AND kode_alternatif='$_GET[kode_alternatif]'");
            print_msg('Data berhasil diubah.', 'success');
        }
    }

     /** PERIODE */
     elseif($mod=='atur_periode_tambah'){
        $tahun = $_POST['tahun'];
        if( $tahun=='')
            print_msg("Field bertanda * tidak boleh kosong!");
        elseif($db->get_results("SELECT * FROM tb_periode WHERE tahun='$tahun'"))
            print_msg("Tahun sudah ada!");
        else{
            $db->query("INSERT INTO tb_periode (tahun) VALUES ('$tahun')");
         
            redirect_js("index.php?m=atur_periode");
        }
    }
    else if ($mod=='atur_periode_status_ok'){
        if($_GET['ID']==''){
            print_msg('Pilih periode terlebih dulu.');
        }else{
            $db->query("UPDATE tb_periode SET status='0' WHERE tahun!=''");
            $db->query("UPDATE tb_periode SET status='1' WHERE id='$_GET[ID]'");
            // print_msg('Status berhasil diubah.', 'success');
            redirect_js("index.php?m=atur_periode");

        }
    }
    else if ($mod=='atur_periode_ubah'){
        if($_GET['ID']==''){
            print_msg('Pilih periode terlebih dulu.');
        }else{
            $db->query("UPDATE tb_periode SET tahun='$_POST[tahun]' WHERE id='$_GET[ID]'");
            // print_msg('Data berhasil diubah.', 'success');
            redirect_js("index.php?m=atur_periode");
        }
    }

