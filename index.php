<?php get_header(); ?>
<main role="main">

  <?php if (my_blog_page()):  ?>
  <div class="blog_header_image">
    <?php dynamic_sidebar('Blog Header Image'); ?>
  </div>
  <?php endif; ?>

  <div class="row">
  <?php
    if (have_posts() || my_blog_page()) {

      //feat. banner
      if (is_front_page()) {get_template_part('partials','head'); }

      if (my_blog_page()): ?>
        <!-- blog pages -->
        <?php get_template_part('partials','blog'); ?>

      <?php else: ?>
        <!-- everything else -->
        <div class="twelve columns">
          <?php the_post(); ?>
          <article>
            <?php the_content(); ?>
          </article>

          <?php if (is_front_page()) { get_template_part( 'partials', 'home_middle' ); } ?>
        </div>

      <?php
      endif;

    } elseif (is_404()) {
        get_template_part('partials','404');
    }
  ?>

  </div>
</main>
<?php get_footer(); ?>