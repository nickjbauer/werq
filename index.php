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

      if (my_blog_page()){
        get_template_part('partials','blog');

      } else {
        //feat. banner
        get_template_part('partials','head');
        the_post();

        //see if general page or event page
        if (my_find_a_class()) {
          get_template_part('partials', 'find_a_class');
        } else if(my_find_a_virtual_class()) {
          get_template_part('partials', 'find_a_virtual_class');
        } else {
          get_template_part('partials', 'page');
        }
      }

    } elseif (is_404()) {
        get_template_part('partials','404');
    }
?>

  </div>
</main>
<?php get_footer(); ?>