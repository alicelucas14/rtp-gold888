<div class="row">
<div class="col-12 mb-3">
    <div class="card border border-warning">
                    <form method="POST" action="config/add-sliders.php" class="form-horizontal">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-image"></i> Add Sliders</h4>
                            <small><?php echo $uploadgambar; ?></small>
                            <div class="form-group mt-3 row">
                                <label class="col-sm-2 text-right control-label col-form-label">Slider Image Link:</label>
                                <div class="col-sm-10">
                                    <input data-toggle="tooltip" title="Link Download In IMGBB OR go to DASHBOARD !" type="text" class="form-control" name="addsliders" placeholder="Link Image Sliders" required>
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

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title m-b-0">List of Sliders</h5>
                            </div>
                            <table class="table font-weight-bold text-center">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Link Sliders</th>
                                      <th scope="col">Images</th>
                                      <th scope="col">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>

                                  <?php
                                    $displayimg = "SELECT * FROM img_sliders ORDER BY id DESC";
                                    $bindimg = mysqli_query($data, $displayimg);

                                        if(mysqli_num_rows($bindimg) > 0) {
                                            $n = 1;
                                            while($fetchimg = mysqli_fetch_assoc($bindimg)) {
                                                $imgsliders = $fetchimg['sliders'];
                                                $idimg = $fetchimg['id'];

                                                echo '<tr>
                                                <th scope="row">'.$n++.'</th>
                                                <form method="POST" action="config/updatesliders.php?row='.$idimg.'">
                                                <td><input name="update-img" type="text" value="'.$imgsliders.'" class="form-control border border-warning" /></td>
                                                <td><a href="'.$imgsliders.'" target="_blank"><img src="'.$imgsliders.'" width="130px;" /></a></td>
                                                <td><button type="submit" class="btn btn-info">Edit</button> <a href="config/deleteslider.php?row='.$idimg.'" class="btn btn-danger"><i class="fas fa-times"></i> Remove</a></td>
                                                </form>
                                                </tr>
                                                ';

                                            }

                                        } else {

                                        }

                                    ?>


                                    

                                  </tbody>
                            </table>
                        </div>

    </div>

</div>