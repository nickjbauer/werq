<?php
/*
Template Name: Half-Width
*/

get_header();
?>

<main role="main">
 <?php
  if (have_posts()) {

    get_template_part( 'partials', 'head' );

    the_post();
  ?>
    <article>
      <?php
      if (get_the_ID() == 49 || get_the_ID() == 5987) { //contact
      ?>
        <div class="row">
          <div class="six columns">
            <?php
              if (!empty(get_post_meta(get_the_ID(),'form',true))) {
                echo do_shortcode(get_post_meta(get_the_ID(), 'form', true));
              }
            ?>
          </div>
          <div class="six columns">
            <?php the_content(); ?>
          </div>
        </div>
      <?php
      }
      ?>
    </article>
<?php
  }
?>
</main>
<?php get_footer(); ?>