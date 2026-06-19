<div class="row">
        <div class="col-md-12">
                <div class="card border border-danger">
                    <form method="POST" action="config/update-runtxt.php" class="form-horizontal">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-pencil-alt"></i> Running Text</h4>
                            <div class="form-group row">
                                <label class="col-sm-3 text-right control-label col-form-label">Update Text:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="runningtxt" value="<?php ftab("running_text", "change_text", "running_text") ?>" placeholder="Running Text" required>
                                </div>

                                <label class="col-sm-3 text-right control-label col-form-label">Update Kecepetan Teks Berjalan:</label>
                                <div class="col-sm-9">
                                    <input type="number" min="0" class="form-control" name="speedtxt" value="<?php ftab("scroll_amount", "change_text", "scroll_amount") ?>" placeholder="Kecepatan Teks Berjalan" required>
                                </div>
                            </div>
                            

                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn float-right btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>

        <div class="col-xl-12">
        <div class="card border border-info">
            <form method="POST" action="config/update-homepagetxt.php" class="form-horizontal">
                <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-pencil-alt"></i> Homepage Text</h4>
                        <small><?php echo $uploadgambar; ?></small>
                        <textarea class="editor" name="homepage-text" style="height: 300px; width: 100%;"><?php ftab("homepage_text", "change_text", "homepage_text") ?></textarea>

                        <button type="submit" class="btn mt-3 btn-block btn-primary">Submit</button>
                           
                        </form>
                 </div>
         </div>


          <div class="col-xl-12">
        <div class="card border border-info">
            <form method="POST" action="config/update-footer-text.php" class="form-horizontal">
                <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-pencil-alt"></i> Footer Text</h4>

                        <textarea name="footer-text" class="editor"><?php ftab("footer_text", "change_text", "footer_text"); ?></textarea>
                        <button type="submit" class="btn mt-3 btn-block btn-primary">Submit</button>

                        </form>
                 </div>
         </div>

         
</div>


