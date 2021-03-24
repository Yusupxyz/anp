<div class="page-header">
    <h1>Tambah Periode</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Tahun Periode Pemilihan <span class="text-danger">*</span></label>
                <input class="form-control" type="number" min="1900" max="2099" step="1" name="tahun" value="<?=set_value('tahun')?>"/>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>