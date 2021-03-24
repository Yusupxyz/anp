<?php
error_reporting(~E_NOTICE & ~E_DEPRECATED);
session_start();

include'config.php';
include'includes/ez_sql_core.php';
include'includes/ez_sql_mysqli.php';
$db = new ezSQL_mysqli($config['username'], $config['password'], $config['database_name'], $config['server']);
include'includes/general.php';  
    
$mod = $_GET['m'];
$act = $_GET['act'];  

$tahun = $db->get_results("SELECT id FROM tb_periode WHERE status='1'")[0]->id;
$rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE id_periode='$tahun' ORDER BY kode_alternatif");
$ALTERNATIF = array();
foreach($rows as $row){
    $ALTERNATIF[$row->kode_alternatif] = $row->nama_alternatif;
}

$rows = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria ORDER BY kode_kriteria");
$KRITERIA = array();
foreach($rows as $row){
    $KRITERIA[$row->kode_kriteria] = $row->nama_kriteria;
}

$nRI = array (
	1=>0,
	2=>0,
	3=>0.58,
	4=>0.9,
	5=>1.12,
	6=>1.24,
	7=>1.32,
	8=>1.41,
	9=>1.46,
	10=>1.49
);

function get_supermatriks(){
    global $ALTERNATIF, $KRITERIA;
    $arr = array();
    foreach($ALTERNATIF as $key => $val){
        foreach($ALTERNATIF as $k => $v){
            $arr[$key][$k] = ($key==$k) ? 1 : 0;
        }               
        
        $data = get_alt_krit();
        foreach($KRITERIA as $k => $v){
            $arr[$key][$k] = $data[$key][$k];
        }
    }
    
    foreach($KRITERIA as $key => $val){
        $data = get_krit_alt();
        foreach($ALTERNATIF as $k => $v){
            $arr[$key][$k] = $data[$key][$k];
        }
        foreach($KRITERIA as $k => $v){
            $arr[$key][$k] = ($key==$k) ? 1 : 0;
        }
    }
    $arr2 = array();
    foreach($arr as $key => $val){
        foreach($val as $k => $v){
            $arr2[$k][$key] = $v;
        }
    }
    return $arr2;
}

function get_weighted_supermatriks($supermatriks){
    $arr = array();
    foreach($supermatriks as $key => $val){
        foreach($val as $k => $v){
            $arr[$key][$k] = $v / 2;
        }
    }
    return $arr;
}

function perkalian_matriks($matriks_a = array(), $matriks_b = array()){
    $arr = array();
    foreach($matriks_a as $i => $val){
        foreach($matriks_b as $j => $v){
            //echo "$i * $j <br />";
            $temp = 0;
            foreach($matriks_b as $k => $b){
                $temp+= $matriks_a[$i][$k] * $matriks_b[$k][$j];
                //echo " {$matriks_a[$i][$k]} * {$matriks_b[$k][$j]} <br />";
            }
            //echo '<br />';
            $arr[$i][$j] = $temp;
        }        
    }
    return $arr;
}

function get_alt_krit(){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_alt_krit ORDER BY kode_alternatif, kode_kriteria");
    $arr = array();
    foreach($rows as $row){
        $arr[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
    }    
    return $arr;
}

function get_krit_alt(){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_krit_alt ORDER BY kode_kriteria, kode_alternatif");
    $arr = array();
    foreach($rows as $row){
        $arr[$row->kode_kriteria][$row->kode_alternatif] = $row->nilai;
    }    
    return $arr;
}

function get_relkriteria($alternatif=''){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_rel_kriteria WHERE kode_alternatif='$alternatif' ORDER BY kode1, kode2");
    $matriks = array();
    foreach($rows as $row){
        $matriks[$row->kode1][$row->kode2] = $row->nilai;
    }    
    return $matriks;
}

function get_relalternatif($kriteria=''){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE kode_kriteria='$kriteria' ORDER BY kode1, kode2");
    $matriks = array();
    foreach($rows as $row){
        $matriks[$row->kode1][$row->kode2] = $row->nilai;
    }
    return $matriks;
}

function get_hasilperiode($kriteria=''){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE id_periode='$kriteria' ORDER BY rank");
    return $rows;
}

function get_tahun($kriteria=''){
    global $db;
    $rows = $db->get_results("SELECT tahun FROM tb_periode WHERE id='$kriteria'")[0]->tahun;
    return $rows;
}

function get_tahun_aktif(){
    global $db;
    $rows = $db->get_results("SELECT tahun FROM tb_periode WHERE status='1'")[0]->tahun;
    return $rows;
}

function get_tahun_aktif_id(){
    global $db;
    $rows = $db->get_results("SELECT id FROM tb_periode WHERE status='1'")[0]->id;
    return $rows;
}

function get_kriteria_option($selected = ''){
    global $db;
    $rows = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria ORDER BY kode_kriteria");
    foreach($rows as $row){
        if($row->kode_kriteria==$selected)
            $a.="<option value='$row->kode_kriteria' selected>$row->kode_kriteria - $row->nama_kriteria</option>";
        else
            $a.="<option value='$row->kode_kriteria'>$row->kode_kriteria - $row->nama_kriteria</option>";
    }
    return $a;
}

function get_alternatif_option($selected = ''){
    global $db;
    $tahun = $db->get_results("SELECT id FROM tb_periode WHERE status='1'")[0]->id;
    $rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif WHERE id_periode='$tahun' ORDER BY kode_alternatif");
    foreach($rows as $row){
        if($row->kode_alternatif==$selected)
            $a.="<option value='$row->kode_alternatif' selected>$row->kode_alternatif - $row->nama_alternatif</option>";
        else
            $a.="<option value='$row->kode_alternatif'>$row->kode_alternatif - $row->nama_alternatif</option>";
    }
    return $a;
}

function get_nilai_option($selected = ''){
    $nilai = array(
        '1' => 'Sama penting dengan',
        '2' => 'Mendekati sedikit lebih penting dari',
        '3' => 'Sedikit lebih penting dari',
        '4' => 'Mendekati lebih penting dari',
        '5' => 'Lebih penting dari',
        '6' => 'Mendekati sangat penting dari',
        '7' => 'Sangat penting dari',
        '8' => 'Mendekati mutlak dari',
        '9' => 'Mutlak sangat penting dari',
    );
    foreach($nilai as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$key - $value</option>";
        else
            $a.= "<option value='$key'>$key - $value</option>";
    }
    return $a;
}

function get_periode_option($selected = ''){
    global $db;
    $rows = $db->get_results("SELECT id, tahun FROM tb_periode ORDER BY id");
    foreach($rows as $row){
        if($row->id==$selected)
            $a.="<option value='$row->id' selected>$row->tahun</option>";
        else
            $a.="<option value='$row->id'>$row->tahun</option>";
    }
    return $a;
}

function get_total_kolom($matriks = array()){
    $total = array();        
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $total[$k]+=$v;
        }
    }  
    return $total;
} 

function get_normalize($matriks = array(), $total = array()){
          
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $matriks[$key][$k] = $matriks[$key][$k]/$total[$k];
        }
    }     
    return $matriks;       
}

function get_rata($normal){
    $rata = array();
    foreach($normal as $key => $value){
        $rata[$key] = array_sum($value)/count($value); 
    } 
    return $rata;   
}

function get_mmult($matriks = array(), $rata = array()){
	$data = array();
    
    $rata = array_values($rata);
    
	foreach($matriks as $key => $value){
        $no=0;
		foreach($value as $k => $v){
			$data[$key]+=$v*$rata[$no];       
            $no++;  
		}				
	}  
    
	return $data;
}

function get_consistency_measure($matriks, $rata){
    $matriks = get_mmult($matriks, $rata);    
    foreach($matriks as $key => $value){
        $data[$key]=$value/$rata[$key];        
    }
    return $data;
}

function get_eigen_alternatif($kriteria=array()){
    $data = array();
    foreach($kriteria as $key => $value){
        $kode_kriteria = $key;
        $matriks = get_relalternatif($kode_kriteria);
        $total = get_total_kolom($matriks);
        $normal = get_normalize($matriks, $total);
        $rata = get_rata($normal);
        $data[$kode_kriteria] = $rata;                
    }
    $new = array();
    foreach($data as $key => $value){
        foreach($value as $k => $v){
            $new[$k][$key] = $v;
        }
    }
    return $new;
}

function get_rank($array){
    $data = $array;
    arsort($data);
    $no=1;
    $new = array();
    foreach($data as $key => $value){
        $new[$key] = $no++;
    }
    return $new;
}