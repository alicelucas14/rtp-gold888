<div class="row">

<div class="col-xl-12">
<h3>Welcome to Z-PANEL admin</h3>
<hr>
</div>

<div class="col-xl-6 mb-2">
        <form method="POST" action="config/add-logo.php" class="form-horizontal">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-mobile"></i> CHANGE LOGO</h4>
                    <input value="<?php ftab("logo_rtp", "web_setting", "logo_rtp") ?>" data-toggle="tooltip" title="Link Download In IMGBB OR go to DASHBOARD !" type="text" class="mt-3 form-control border border-info" name="logolink" placeholder="Logo Image Link" required>
                    <img class="mt-3" src="<?php ftab("logo_rtp", "web_setting", "logo_rtp") ?>" alt="No Image Logo Found" width="150" />
                    <button type="submit" class="btn mt-3 float-right btn-primary">Submit</button>
                </div>
            </div>
        </form>

        
</div>

<div class="col-xl-6 mb-2">
        <form method="POST" action="config/add-linkbo.php" class="form-horizontal">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-link"></i> CHANGE LINKS DAFTAR & LOGIN</h4>
                    <input value="<?php ftab("link_daftarbo", "web_setting", "link_daftarbo") ?>" data-toggle="tooltip" title="Link Untuk Daftar ke BO !" type="text" class="mt-3 form-control border border-info" name="daftarlink" placeholder="Daftar Link" required>
                    <input value="<?php ftab("link_masukbo", "web_setting", "link_masukbo") ?>" data-toggle="tooltip" title="Link Untuk Daftar ke BO !" type="text" class="mt-3 form-control border border-info" name="loginlink" placeholder="Login Link" required>
                    <button type="submit" class="btn mt-3 float-right btn-primary">Submit</button>
                </div>
            </div>
        </form>

        
</div>

<div class="col-xl-6 mb-2">
        <form method="POST" action="config/update-bg.php" class="form-horizontal">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-link"></i> Update Gambar Background Web RTP</h4>
                    <input value="<?php ftab("bg_rtp", "web_setting", "bg_rtp") ?>" data-toggle="tooltip" title="Background Web" type="text" class="mt-3 form-control border border-info" name="bgweb" placeholder="Isi Link Gambar background" required>
                        <img src="<?php ftab("bg_rtp", "web_setting", "bg_rtp") ?>" class="my-2" style="width: 130px;" alt="Gambar tidak benar / tidak ada">
                    <button type="submit" class="btn mt-3 float-right btn-primary">Submit</button>
                </div>
            </div>
        </form>

        
</div>

<div class="col-xl-6 mb-2">
        <form method="POST" action="config/add-footerweb.php" class="form-horizontal">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="fas fa-pencil"></i> CHANGE FOOTER WEB</h4>
                    <textarea class="editor" class="mt-3 form-control border border-info" name="footerweb">
                        <?php ftab("footer_web", "change_text", "footer_web") ?>
                    </textarea>
                    <button type="submit" class="btn mt-3 float-right btn-primary">Submit</button>
                </div>
            </div>
        </form>

        
</div>


    <div class="col-xl-6">
              <div class="card border border-warning">
                    <form method="POST" action="config/add-con.php" class="form-horizontal">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-mobile"></i> CHANGE CONTACT INFORMATION</h4>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <!-- <input data-toggle="tooltip" title="Gambar Kontak" type="text" class="form-control" name="addconimg" placeholder="Link Gambar Kontak" required> -->
                                    <textarea class="editor" name="contact-text" style="height: 300px; width: 100%;"><?php ftab('isi_kontak', 'contact_kami', 'isi_kontak'); ?></textarea>
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

             </div>
             



        <p>We have some list for you to understand the method to Add / Edit / Update Image in Zpanel.</p>
        <ol>
                <li>To update image you must go to this website <a href="https://imgbb.com/" target="_blank">IMGBB (Click Here)</a>. <br><br>
                    <img src="assets/images/tutorial/img-1.PNG" class="img-fluid border border-danger" alt=""> <hr>
                </li>

                <li>Click Start Uploading and find the image you want to upload. Click Upload button to upload the image. <strong>Always select "DONT AUTODELETE" the image in the "auto delete image form"</strong> <br><br>
                    <img src="assets/images/tutorial/img-2.PNG" class="img-fluid border border-danger" alt=""> <hr>
                </li>

                <li>After upload change the "Embed Codes" to "HTML full linked" <br><br>
                    <img src="assets/images/tutorial/img-3.PNG" class="img-fluid border border-danger" alt=""> <hr>
                </li>

                <li>Copy the link only until the pic format just like the picture below. Example the image I upload format is <strong>.PNG</strong> <br><br>
                    <img src="assets/images/tutorial/img-4.PNG" class="img-fluid border border-danger" alt=""> <hr>
                </li>

                <li>Paste the link of the image that you desire to change / edit the Image of your WEBPAGE. <br><br>
                    <img src="assets/images/tutorial/img-5.PNG" class="img-fluid border border-danger" alt=""> <hr>
                </li>
        </ol>
    </div>
</div>