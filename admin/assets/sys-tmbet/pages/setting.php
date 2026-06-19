<div class="row">

    <?php
        $selectseo = "SELECT content_seo, id FROM web_setting";
        $bindseo = mysqli_query($data, $selectseo);

        if(mysqli_num_rows($bindseo) > 0) {
            while($fetchseo = mysqli_fetch_assoc($bindseo)) {
                $seo = $fetchseo['content_seo'];
                $seoid = $fetchseo['id'];

                echo '<div class="col-12">
        <div class="card border border-warning">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-image"></i> SEO Content WEB </h4>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <form method="POST" action="config/update-seo.php?seo='.$seoid.'" class="form-horizontal">
                                    <textarea name="seo-text" style="height: 300px; width: 100%;">'.$seo.'</textarea>
                                </div>
                            </div>

                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
    </div>
    
    <hr />';

            }
        }

    ?>

<?php
                $sscript2 = "SELECT content_script2, id FROM web_setting";
                $bindscript2 = mysqli_query($data, $sscript2);

                if(mysqli_num_rows($bindscript2) > 0) {
                    while($fetchscript2 = mysqli_fetch_assoc($bindscript2)) {

                        $scripts2 = $fetchscript2['content_script2'];
                        $sid2 = $fetchscript2['id'];


                        echo '<div class="col-12">
                                <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Edit Script Web Tengah </h3>
                                <form method="POST" action="config/update-script2.php?row='.$sid2.'">
                                 <textarea name="script-text2" style="height: 300px; width: 100%;">'.$scripts2.'</textarea>
                                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                                </form>
                                                        
                            </div>
                        </div>
                        ';
                    }
                }
            ?>


            <?php
                $sscript = "SELECT content_script, id FROM web_setting";
                $bindscript = mysqli_query($data, $sscript);




                if(mysqli_num_rows($bindscript) > 0) {
                    while($fetchscript = mysqli_fetch_assoc($bindscript)) {

                        $scripts = $fetchscript['content_script'];
                        $sid = $fetchscript['id'];


                        echo '<div class="col-12">
                                <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Edit Script Web Bawah </h3>
                                <form method="POST" action="config/update-script.php?row='.$sid.'">
                                 <textarea name="script-text" style="height: 300px; width: 100%;">'.$scripts.'</textarea>
                                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                                </form>
                                                        
                            </div>
                        </div>
                        ';
                    }
                }
            ?>
        </div>



</div>