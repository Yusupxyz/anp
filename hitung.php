<div class="page-header">
    <h1>Perhitungan</h1>
</div>
<?php    
    $c = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE nilai>0");
    if (!$ALTERNATIF|| !$KRITERIA):
        echo "Tampaknya anda belum mengatur alternatif dan kriteria. Silahkan tambahkan minimal 3 alternatif dan 3 kriteria.";
    elseif (!$c):
        echo "Tampaknya anda belum mengatur nilai alternatif. Silahkan atur pada menu <strong>Nilai Bobot</strong> > <strong>Nilai Bobot Alternatif</strong>.";
    else:
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Supermatrix</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th></th>
                <?php 
                $supermatriks = get_supermatriks(); 
                $baris_total = get_total_kolom($supermatriks);                       
                foreach($supermatriks as $key => $val):?>
                <th><?=$key?></th>        
                <?php endforeach?>
            </tr></thead>
            <?php foreach($supermatriks as $key => $val):?>
            <tr>
                <td><?=$key?></td>
                <?php foreach($val as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>    
             </tr>  
             <?php endforeach?> 
             <tfoot><tr>
                <td>Total</td>
                <?php foreach($baris_total as $key => $val):?>
                <td><?=$val?></td>
                <?php endforeach?>                
             </tr></tfoot>
        </table>
    </div>
</div>


<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Weighted Supermatrix</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th></th>
                <?php 
                $ws = get_weighted_supermatriks($supermatriks); 
                $baris_total = get_total_kolom($ws);                          
                foreach($ws as $key => $val):?>
                <th><?=$key?></th>        
                <?php endforeach?>
            </tr></thead>
            <?php foreach($ws as $key => $val):?>
            <tr>
                <td><?=$key?></td>
                <?php foreach($val as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>        
             </tr>  
             <?php endforeach?> 
             <tfoot><tr>
                <td>Total</td>
                <?php foreach($baris_total as $key => $val):?>
                <td><?=$val?></td>
                <?php endforeach?>                
             </tr></tfoot>
        </table>
    </div>
</div>

<?php
$limit = $ws;
$total_pangkat = 0;
$batas = 100;

function ulang($matriks){
    global $total_pangkat;
    if($matriks){
        foreach($matriks as $key => $val){
            $nilai = current($val);
            foreach($val as $k => $v){                
                if(round($v, 13)!=round($nilai, 13))
                    return true;
            }
        }        
        return false;
    }    
    return true;
}

while( $total_pangkat < $batas && ulang($limit)){
    $limit = perkalian_matriks($limit, $limit);
    $total_pangkat++;
}                        
                
$baris_total = get_total_kolom($limit);      
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Limit Supermatrix : <?=$total_pangkat?></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th></th>
                <?php 
                foreach($limit as $key => $val):?>
                <th><?=$key?></th>        
                <?php endforeach?>
            </tr></thead>
            <?php foreach($limit as $key => $val):?>
            <tr>
                <td><?=$key?></td>
                <?php foreach($val as $k => $v):?>
                <td><?=round($v, 4)?></td>
                <?php endforeach?>        
             </tr>  
              <?php endforeach?>   
             <tfoot><tr>
                <td>Total</td>
                <?php foreach($baris_total as $key => $val):?>
                <td><?=round($val, 3)?></td>
                <?php endforeach?>                
             </tr></tfoot>                               
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Perankingan</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead><tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Nilai Asal (RAW)</th>
                <th>Nilai Normal</th>
            </tr></thead>
            <?php 
            function get_raw($limit){
                global $ALTERNATIF; 
                $arr = array();

                foreach($ALTERNATIF as $key => $val){
                   $cek=cek_relkriteria($key)[0]->count;
                   if ($cek>0){
                        $arr[$key] = 0;
                   }else{
                        $arr[$key] = current($limit[$key]);
                   }
                }
                return $arr;
            }
            
            function get_total($raw){
                $arr = array();      
                foreach($raw as $key => $val){
                    $arr[$key] = $val / array_sum($raw);
                }    
                return $arr;
            }

            function update_alternatif($kode,$total,$rank){
                global $db;
                $db->query("UPDATE tb_alternatif SET total='$total', rank='$rank' WHERE kode_alternatif='$kode'");          
                return true;
            }

            function cek_relkriteria($alternatif=''){
                global $db;
                $cekrows = $db->get_results("SELECT count(*) as 'count' FROM tb_rel_kriteria WHERE kode_alternatif='$alternatif' AND nilai=0");
                // var_dump($cekrows); 
                return $cekrows;
            }
            
            $raw = get_raw($limit);
            $total = get_total($raw);
            $rank = get_rank($total);
            $i=1;
            foreach($rank as $key => $val):
                 update_alternatif($key,$total[$key],$i++);
            ?>
            <tr>
                <td><?=$key?></td>
                <td><?=$ALTERNATIF[$key]?></td>
                <td><?=round($raw[$key], 4)?></td>
                <td><?=round($total[$key] * 100, 2)?> %</td>
             </tr> 
             <?php endforeach?>                            
        </table>
    </div>
</div>
<?php endif?>