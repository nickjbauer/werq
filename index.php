<?php get_header(); ?>
<main role="main">
<?php
if (have_posts()) {
  while (have_posts()) : the_post(); ?>
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

  if (is_front_page()):
    ?>
    <aside>
      <div class="row">
        <div class="three columns col1">11</div>
        <div class="three columns col2">22</div>
        <div class="three columns col3">33</div>
        <div class="three columns col4">44</div>
      </div>
    </aside>

    <?php
  endif;

}elseif (is_404()){ ?>

  <article>
    <h1>We're sorry...</h1>
    <p>Looks like we can't find the page you are looking for!</p>
  </article>

<?php } ?>

</main>
<?php get_footer(); ?>