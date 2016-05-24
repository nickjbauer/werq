<?php get_header(); ?>
  <main role="main">
    <?php
      if (have_posts()) {
        get_template_part('partials', 'head');
      }
    ?>
    <article>
        <div class="row">
          <div class="twelve columns">
            <?php
              wp_reset_query();

              $id = (int)$_GET['id'];

              $args = array(
                'post_type' => 'event-photo-listing',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'post__in' => array($id)
              );

              $loop = new WP_Query($args);

              if ($loop->have_posts()) {
                $loop->the_post();

                //get general
                $id = get_the_ID();
                $title = get_the_title();
                $content = get_the_content();
                $gallery_id = get_post_meta($id, 'wpcf-gallery-id', true);
              ?>
                <h1><?=$title?></h1>
                <?php if (!empty($content)): ?>
                  <div><?=$content?></div>
                  <div id="gallery">
                    <?php echo do_shortcode('[ngg_images gallery_ids="'.$gallery_id.'" display_type="photocrati-nextgen_basic_thumbnails"]');?>
                  </div>
                <?php endif; ?>
              <?php
              } else {
                  global $wp_query;
                  $wp_query->set_404();
                  status_header( 404 );
                  get_template_part( 'partials','404' );
              }
            ?>
            <div id="back"><a href="/events">< Back To Events</a></div>
          </div>
        </div>
    </article>
  </main>
<?php get_footer(); ?>