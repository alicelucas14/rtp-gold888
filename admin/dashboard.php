<?php
    include 'assets/sys-tmbet/components/header.php';
    require 'assets/config-data.php';
    include 'config/functions.php';
?>
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
         
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
             
                <?php
                      
                      if(isset($_GET['hal'])) {
                          $page = mysqli_real_escape_string($data, $_GET['hal']);

                          switch($page) {
                            case 'changetext' :
                                include 'assets/sys-tmbet/pages/change-text.php';
                                break;
                            case 'sliders' :
                                include 'assets/sys-tmbet/pages/sliders.php';
                                break;
                            case 'gameimg' :
                                include 'assets/sys-tmbet/pages/games.php';
                                break;
                            case 'categories' : // This is the new case for categories
                                include 'assets/sys-tmbet/pages/provider_categories.php';
                                break;
                            case 'providers' :
                                include 'assets/sys-tmbet/pages/providers.php';
                                break;
                            case 'setting' :
                                include 'assets/sys-tmbet/pages/setting.php';
                                break;
                            default:
                                include 'assets/sys-tmbet/pages/main.php';
                                break;
                          }

                      } else {
                        include 'assets/sys-tmbet/pages/main.php';
                      }
                 ?>

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                All Rights Reserved by Z-PANEL.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <?php
    include 'assets/sys-tmbet/components/footer.php';
?>