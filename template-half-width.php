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

    while (have_posts()) : the_post();
  ?>
    <article>
      <?php
      if (get_the_ID() == 49) { //contact
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
      <?
      }
      ?>
    </article>
<?php
    endwhile;
  }
?>
</main>
<?php get_footer(); ?>