<div class="page-header">
    <h1>Nilai Bobot Kriteria</h1>
</div>
<div class="panel panel-default">
<div class="panel-heading">
<form class="form-inline">
    <input type="hidden" name="m" value="rel_kriteria" />
    <div class="form-group">
        <select class="form-control" name="kode_alternatif" onchange="this.form.submit()">
        <option value="">Pilih alternatif</option>
        <?=get_alternatif_option($_GET['kode_alternatif'])?>
        </select>
    </div>
</form>
</div>
<div class="panel-body">
<?php if($_POST) include'aksi.php' ?>
<form class="form-inline" method="post" action="?m=rel_kriteria&kode_alternatif=<?=$_GET['kode_alternatif']?>">
    <input type="hidden" name="m" value="rel_kriteria" />
    <div class="form-group">
        <select class="form-control" name="kode1">
        <?=get_kriteria_option($_POST['kode1'])?>
        </select>
    </div>
    <div class="form-group">
        <select class="form-control" name="nilai">
        <?=get_nilai_option($_POST['nilai'])?>
        </select>
    </div>
    <div class="form-group">
        <select class="form-control" name="kode2">
        <?=get_kriteria_option($_POST['kode2'])?>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
    </div>
</form>
</div>
<?php 
$data = get_relkriteria($_GET['kode_alternatif']); 
if($data):

    $baris_total = get_total_kolom($data);
    $normal = get_normalize($data, $baris_total);
    $rata = get_rata($normal);
    
    foreach($rata as $key => $val){
        $db->query("REPLACE INTO tb_alt_krit (kode_alternatif, kode_kriteria, nilai)
                VALUES ('$_GET[kode_alternatif]', '$key', '$val')");
    }
    
    $cm = get_consistency_measure($data, $rata);
    $CI = ((array_sum($cm)/count($cm))-count($cm))/(count($cm)-1);	
	$RI = $nRI[count($data)];
	$CR = ($RI==0) ? 0 : $CI/$RI;
    if($CR > 0.1):?>
    <div class="panel-body">
        <?=print_msg('Perbandingan yang anda inputkan tidak konsisten. Pastikan mengisi perbandingan dengan sesuai supaya maksimal nilai CR 0.1.')?>
    </div>
    <?php endif?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr class="text-primary">
                <th>Kode</th>
                <?php foreach($data as $key=>$val):?>
                <th><?=$key?></th>               
                <?php endforeach?>
            </tr>
        </thead>
        <?php foreach($data as $key => $val):?>
        <tr>
            <th class="text-primary"><?=$key?></th>
            <?php foreach($val as $k => $v):?>
            <td><?=round($v, 3)?></td>               
            <?php endforeach?>
        </tr>
        <?php endforeach?>
        </table>
    </div>
    <div class="panel-body">
        
    </div>  
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <?php foreach($data as $key => $val):?>
                <th><?=$key?></th>
                <?php endforeach?>
                <th>Bobot</th>
                <th>CM</th>
            </tr>
        </thead>        
        <?php foreach($normal as $key => $val):?>
        <tr>
            <th><?=$key?></th>
            <?php foreach($val as $k => $v ):?>
            <td><?=round($v, 3)?></td>
            <?php endforeach?>
            <td><?=round($rata[$key], 3)?></td>
            <td><?=round($cm[$key], 3)?></td>
        </tr>
        <?php endforeach;?>     
        </table>
    </div>  
    <div class="panel-body">
    <?php        
    	echo "<p>Consistency Index: ".round($CI, 3)."<br />";	
    	echo "Ratio Index: ".round($RI, 3)."<br />";
    	echo "Consistency Ratio: ".round($CR, 3);
    	if($CR>0.10){
    		echo " (Tidak konsisten)<br />";	
    	} else {
    		echo " (Konsisten)<br />";
    	}
    ?>
    </div>
<?php endif?>
</div>