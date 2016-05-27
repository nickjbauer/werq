<!-- everything else -->
<div class="twelve columns">
  <article>
    <?php the_content(); ?>
  </article>

  <?php if (is_front_page()) { get_template_part( 'partials', 'home_middle' ); } ?>
</div>