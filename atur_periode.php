<?php

include'aksi.php';
?>
<div class="page-header">
    <h1>Atur Periode</h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="atur_periode" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?=$_GET['q']?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="?m=atur_periode_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
            </div>
            <!-- <div class="form-group">
                <a class="btn btn-default" href="cetak.php?m=atur_periode&q=<?=$_GET[q]?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Cetak</a>
            </div> -->
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field($_GET['q']);
        $rows = $db->get_results("SELECT * FROM tb_periode 
            WHERE id LIKE '%$q%' 
                OR tahun LIKE '%$q%'
            ORDER BY id");
        $no=0;
        foreach($rows as $row):?>
        <tr>
            <td><?=++$no ?></td>
            <td><?=$row->tahun?></td>
            <td>
                <?php if ($row->status=='1'){ ?>
                    Aktif
                <?php }else{ ?>
                    Tidak aktif
                <?php } ?>
            </td>
            <td>
                <?php 
                if ($row->status=='0'){ ?>
                    <a class="btn btn-xs btn-success" href="?m=atur_periode_status_ok&ID=<?=$row->id?>"><span class="glyphicon glyphicon-ok"></span></a>
                <?php } ?>
                <a class="btn btn-xs btn-warning" href="?m=atur_periode_ubah&ID=<?=$row->id?>"><span class="glyphicon glyphicon-edit"></span></a>
                <!-- <a class="btn btn-xs btn-danger" href="aksi.php?act=atur_periode_hapus&ID=<?=$row->id?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a> -->
            </td>
        </tr>
        <?php endforeach;?>
        </table>
    </div>
</div>