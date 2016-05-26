<div id="blog_pages">
  <div class="nine columns">
    <article>
    <?php
    if (have_posts()) {
      $i = 0;
      echo '<div class="row">';
      while (have_posts()) {
        $i++;
        the_post();
    ?>
        <div class="four columns">
          <div class="feat_img">
            <a href="<?= the_permalink() ?>">
            <?php
            if ( strlen( $img = get_the_post_thumbnail( get_the_ID(), 'medium' ) ) )  {
              //grab feat. image
              the_post_thumbnail('medium');
            } else {
              //grab 1st image of post
              $first_post_img = my_catch_that_image();

              if (!empty($first_post_img)) {
                echo '<div class="crop"><img src="'.$first_post_img.'" class="attachment-medium size-medium wp-post-image feat_img"></div>';
              }
            }
            ?>
            </a>
          </div>
          <h1><?php the_title() ?></h1>
          <span class="blog_date"><?php the_date() ?></span>
          / <span class="blog_cat"><?php the_category( ', ' ); ?></span>

          <?php the_excerpt() ?>
        </div>
    <?
        if ($i % 3 == 0) { echo '</div><div class="row">'; $i = 0; }
      }

      //close it up
        if ($i == 0) {
          echo '<div class="four columns">&nbsp;</div><div class="four columns">&nbsp;</div><div class="four columns">&nbsp;</div>';
        }
        elseif ($i % 2 == 0) {
          echo '<div class="four columns">&nbsp;</div>';
        } else {
          echo '<div class="four columns">&nbsp;</div><div class="four columns">&nbsp;</div>';
        }

      echo '</div>';

      //more link
      echo '<div class="page-link">'.posts_nav_link('<span class="page-link-spacer">&bull;</span>','&laquo; Newer posts  ','  Older posts &raquo;').'</div>';


    } else { ?>
      <div>
        <h1>Sorry search result for "<?=htmlspecialchars($_GET["s"])?>" returns no results.</h1>
        <h2>Perhaps you want to view some of our most recent articles!</h2>
        <?php dynamic_sidebar('No Results'); } ?>
      </div>
    </article>
  </div>
  <div class="three columns">
    <aside>
      <?php get_sidebar(); ?>
    </aside>
  </div>
</div>
