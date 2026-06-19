    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <!-- <script src="dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->
    <script src="assets/libs/flot/excanvas.js"></script>
    <script src="assets/libs/flot/jquery.flot.js"></script>
    <script src="assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="assets/libs/flot/jquery.flot.time.js"></script>
    <script src="assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="dist/js/pages/chart/chart-page-init.js"></script>

    <!-- ============================================================== -->
    <!--                  PAGE-SPECIFIC JAVASCRIPT                      -->
    <!-- ============================================================== -->
    <script>
    $(document).ready(function() {
    
        // --- SCRIPT FOR PROVIDER MANAGEMENT MODAL ---
        $('#editProviderModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var id = button.data('id');
            var name = button.data('name');
            var code = button.data('code');
            var rating = button.data('rating');
            var categoryId = button.data('categoryid'); // Get the category ID
            // We get the logo path from the image element itself to avoid issues
            var logo = $(button).closest('tr').find('img').attr('src');

            var modal = $(this);
            modal.find('.modal-title').text('Edit Provider: ' + name);
            modal.find('#edit_provider_id').val(id);
            modal.find('#edit_provider_name').val(name);
            modal.find('#edit_provider_code').val(code);
            modal.find('#edit_provider_rating').val(rating);
            modal.find('#edit_category_id').val(categoryId); // Set the selected category in the dropdown
            
            // The logo path is now relative from the PHP file, so no need for '../'
            modal.find('#current_logo_img').attr('src', logo);
        });

        // --- SCRIPT FOR DEMO SLOT (GAMES) MODALS ---
        $('#editGameModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var title = button.data('title');
            var link = button.data('link');
            var image = button.data('image');

            var modal = $(this);
            modal.find('.modal-title').text('Edit Game: ' + title);
            modal.find('#edit_game_id').val(id);
            modal.find('#edit_game_title').val(title);
            modal.find('#edit_game_link').val(link);
            modal.find('#edit_game_image_preview').attr('src', image + '?t=' + new Date().getTime());
        });

        $('#deleteGameModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var title = button.data('title');
            
            var modal = $(this);
            modal.find('#delete_game_id').val(id);
            modal.find('#delete_game_title').text(title);
        });

        // --- SCRIPT FOR CATEGORY MODALS ---
        $('#editCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var order = button.data('order');
            var active = button.data('active');
            var logo = button.data('logo');

            var modal = $(this);
            modal.find('#edit_category_id').val(id);
            modal.find('#edit_category_name').val(name);
            modal.find('#edit_display_order').val(order);
            modal.find('#edit_is_active').val(active);
            
            if (logo) {
                // The logo path from the `data-logo` attribute is already correct
                modal.find('#edit_category_logo_preview').attr('src', '../' + logo + '?t=' + new Date().getTime()).show();
            } else {
                modal.find('#edit_category_logo_preview').attr('src', '').hide();
            }
        });

        $('#deleteCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            
            var modal = $(this);
            modal.find('#delete_category_id').val(id);
            modal.find('#delete_category_name').text(name);
        });

    });
    </script>

</body>

</html>