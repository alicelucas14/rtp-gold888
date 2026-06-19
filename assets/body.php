<main class="container">
    <div class="logo">
        <img src="<?php echo ftab("logo_rtp", "web_setting", "logo_rtp"); ?>" class="img-fluid" alt="logo rtp">
    </div>

    <!-- Slider main container -->
    <div class="slider-wrapper rounded-top shadow">
        <div class="running-text">
            <marquee scrollamount="<?php echo ftab("scroll_amount", "change_text", "scroll_amount"); ?>" direction="left"><?php echo ftab("running_text", "change_text", "running_text"); ?></marquee>
        </div>
        <div class="swiper slider">
            <div class="swiper-wrapper">
                <?php
                $sslide = "SELECT sliders FROM img_sliders";
                $qslide = mysqli_query($data, $sslide);
                if (mysqli_num_rows($qslide) > 0) {
                    while ($fslide = mysqli_fetch_assoc($qslide)) {
                        echo '<div class="swiper-slide"><img src="' . htmlspecialchars($fslide['sliders']) . '" loading="lazy" class="slider-img rounded" alt="slider image"><div class="swiper-lazy-preloader"></div></div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row g-0">
        <div class="col d-grid">
            <button onclick='location.href="<?php echo ftab("link_masukbo", "web_setting", "link_masukbo"); ?>";' class="btn-credit">LOGIN</button>
        </div>
        <div class="col d-grid">
            <button onclick='location.href="<?php echo ftab("link_daftarbo", "web_setting", "link_daftarbo"); ?>";' class="btn-credit">DAFTAR</button>
        </div>
    </div>

    <?php
    $category_sql = "SELECT id, category_name, category_logo FROM provider_categories WHERE is_active = 1 ORDER BY display_order ASC";
    $category_query = mysqli_query($data, $category_sql);

    if (mysqli_num_rows($category_query) > 0) {
        while ($category = mysqli_fetch_assoc($category_query)) {
            $category_id = $category['id'];
            $category_name = htmlspecialchars($category['category_name']);
            $category_logo = htmlspecialchars($category['category_logo'] ?? '');

            $provider_sql = "SELECT provider_code, provider_name, provider_logo FROM providers WHERE is_active = 1 AND category_id = $category_id ORDER BY provider_name ASC";
            $provider_query_inner = mysqli_query($data, $provider_sql);

            if (mysqli_num_rows($provider_query_inner) > 0) {
                echo '<div class="category-header">';
                if (!empty($category_logo)) {
                    echo '<img src="' . $category_logo . '" class="category-logo" alt="' . $category_name . '">';
                }
                echo '<h4>' . $category_name . '</h4></div>';
                
                echo '<div class="icon-prov g-1">';
                while ($provider = mysqli_fetch_assoc($provider_query_inner)) {
                    $p_code = htmlspecialchars($provider['provider_code']);
                    $p_name = htmlspecialchars($provider['provider_name']);
                    $p_logo = htmlspecialchars($provider['provider_logo']);
                    echo '<div class="item-prov"><div onclick="linkProv(\'' . $p_code . '\')" class="icon-card-bg"><div class="p-2"><img src="' . $p_logo . '" class="img-prov" alt="Logo ' . $p_name . '"></div><div class="p-2"><p>' . $p_name . '</p></div></div></div>';
                }
                echo '</div>';
            }
        }
    }
    ?>

    <div class="bg-theme">
        <div class="d-flex justify-content-between">
            <div class="mt-3">
                <h6><i class="lni text-warning lni-timer"></i> Update RTP: <?php echo dayindo(); ?>, <?php echo date("d"); ?> <?php echo bulanindo(); ?> <?php echo date("Y"); ?></h6>
            </div>
            <div class="my-2">
                <button onclick="darkMode()" id="btn-colorscheme" class="btn btn-sm btn-light"><i id="icon-colorscheme" class="lni lni-sun"></i></button>
            </div>
        </div>

        <div class="row justify-content-center g-1">
            <?php
            $prov = 'pp'; // Default provider
            if (isset($_GET['game'])) {
                $prov = mysqli_real_escape_string($data, $_GET['game']);
            }

            // [MODIFIED] Fetch the provider_logo along with other details
            $s_provider_details = "SELECT provider_name, provider_rating, provider_logo FROM providers WHERE provider_code = '$prov' AND is_active = 1 LIMIT 1";
            $q_provider_details = mysqli_query($data, $s_provider_details);

            if (mysqli_num_rows($q_provider_details) > 0) {
                $provider_details = mysqli_fetch_assoc($q_provider_details);
                $provider_name_display = strtoupper($provider_details['provider_name']);
                $provider_rating_display = (float)$provider_details['provider_rating'];

                // [NEW] Use the smart function to get the correct icon path
                $provider_icon_url = find_provider_icon_url($provider_details['provider_logo']);

                $stars_html = '';
                for ($i = 1; $i <= 6; $i++) {
                    $star_class = ($i <= $provider_rating_display) ? 'text-warning' : 'text-light';
                    $stars_html .= '<i class="lni lni-star-fill ' . $star_class . '"></i> ';
                }

                echo '<div class="col-12"><h4 class="title-game">' . htmlspecialchars($provider_name_display) . ' SLOT LIVE RTP</h4><h6><i class="lni lni-thumbs-up"></i> SUKA(' . number_format($provider_rating_display, 1) . '): ' . $stars_html . '</h6></div>';

                $daftar = ftab("link_daftarbo", "web_setting", "link_daftarbo");
                $games_sql = "SELECT demo_name FROM demo_games WHERE demo_provider = '$prov' ORDER BY id ASC";
                $games_query = mysqli_query($data, $games_sql);
                
                if (mysqli_num_rows($games_query) > 0) {
                    $num = 1;
                    while ($game = mysqli_fetch_assoc($games_query)) {
                        $game_image_path = find_public_game_image_url($game['demo_name']);
                        // [MODIFIED] Pass the correct provider icon path to the rtpCard function
                        echo rtpCard($prov, $game_image_path, $provider_icon_url, $daftar, $num++);
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">No games found for this provider.</p></div>';
                }
            } else {
                echo '<div class="col-12"><h4 class="title-game">PROVIDER NOT FOUND</h4></div>';
            }
            ?>
        </div>
    </div>
</main>

<footer class="container">
    <div class="mt-1 content-home">
        <?php echo ftab("homepage_text", "change_text", "homepage_text"); ?>
    </div>
</footer>

<button onclick="goUp()" id="btn-up" class="btn-up btn-sm btn-danger"><i class="lni lni-chevron-up-circle"></i></button>

<div class="nav-bottom">
    <div onclick="location.href='/'" class="col item-nav-bottom"><i class="lni lni-home"></i><p>Home</p></div>
    <div onclick='location.href="<?php echo ftab("link_masukbo", "web_setting", "link_masukbo"); ?>";' class="col item-nav-bottom"><i class="lni lni-invest-monitor"></i><p>Promosi</p></div>
    <div onclick='location.href="<?php echo ftab("link_daftarbo", "web_setting", "link_daftarbo"); ?>";' class="col item-nav-bottom"><i class="lni lni-list"></i><p>Daftar</p></div>
    <div data-bs-toggle="modal" data-bs-target="#contact" class="col item-nav-bottom"><i class="lni lni-phone-set"></i><p>Contact</p></div>
</div>

<div class="modal fade" id="contact" tabindex="-1" aria-labelledby="contact" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-dark fs-5" id="contact">Hubungi Kami:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <?php echo ftab("isi_kontak", "contact_kami", "isi_kontak"); ?>
            </div>
        </div>
    </div>
</div>