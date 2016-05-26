<?php get_header(); ?>
<main role="main">
  <div class="row">

<?php if (have_posts() || my_blog_page()) { ?>

      <?php if (my_blog_page()): ?>

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

      <?php endif; ?>

<?php } elseif (is_404()) { get_template_part('partials','404'); } ?>

  </div>
</main>
<?php get_footer(); ?>