<div id="blog_pages">
  <div class="nine columns">
    <article>

      <h1><?php
          if (is_search()) {
            echo 'WERQAholic Search For "'.htmlspecialchars($_GET["s"]).'"';
          }
          elseif (is_home()) {
            echo 'The Blog';
          }
          elseif (is_archive()) {
            $cat = get_the_category();
            echo $cat[0]->name;
          }
          elseif (is_single()) {
            echo the_title();
          }

        ?></h1>

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
          <h2><?php the_title() ?></h2>

          <div class="blog_meta">
            <span class="blog_date"><?php the_date() ?></span>
            / <span class="blog_cat"><?php the_category( ', ' ); ?></span>
          </div>

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
      echo '<hr>';
      echo '<div  class="page-link">';
      echo posts_nav_link('<span class="page-link-spacer">&bull;</span>','&laquo; Newer posts  ','  Older posts &raquo;');
      echo '</div>';

    } else { ?>
      <h3>Sorry search result for "<?=htmlspecialchars($_GET["s"])?>" returns no results.</h3>
      <p>Perhaps you want to view some of our most recent articles!</p>
      <?php dynamic_sidebar('No Results'); } ?>
    </article>
  </div>
  <div class="three columns">
    <aside>
      <?php get_sidebar(); ?>
    </aside>
  </div>
</div>
