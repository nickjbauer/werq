<?php get_header(); ?>
<main role="main">

<?php
  if (have_posts()) {

    get_template_part( 'partials', 'head' );

    while (have_posts()) : the_post();
      ?>
      <article>
        <?php the_content(); ?>
      </article>

      <!--
      <aside>
        <?php get_sidebar(); ?>
      </aside>
      -->

      <?php
    endwhile;

    if (is_front_page()) {
      get_template_part( 'partials', 'home_middle' );
    }

  } elseif (is_404()) {
      get_template_part('partials','404');
  }
?>

</main>
<?php get_footer(); ?>