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
        <div class="three columns callout col1">
          sign up for free
          <h3>WERQ<span>outs</span></h3>

          <form method="post" action="//chicagofitnessparties.us2.list-manage.com/subscribe/post?u=9d46ac14124721f21e6c2bc55&id=8f2588374e">
            <input type="email" placeholder="Email Address" name="EMAIL">
            <input type="submit" value="SUBMIT">
          </form>
        </div>
        <div class="three columns callout col2">
          become
          <h3>WERQ</h3>
          instructor
          <form>
            <input type="submit" value="SIGN UP">
          </form>
        </div>
        <div class="three columns callout col3">
          <h3>WERQ<span>force</span></h3>
          members only
          <form>
            <input type="submit" value="LOGIN">
          </form>
        </div>
        <div class="three columns callout col4">
          <a href="//werqfitness.myshopify.com/" target="_blank"><img src="/wp-content/uploads/2016/05/btn_shop.jpg"></a>
          <a href="#"><img src="/wp-content/uploads/2016/05/btn_class.jpg"></a>
        </div>
      </div>

      <div id="seen_col">
        <h1>AS SEEN IN</h1>

        <div class="row">
          <div class="three columns seen col1">
            <img src="/wp-content/uploads/2016/05/ico-self.jpg" alt="SELF" title="SELF">
          </div>
          <div class="three columns seen col2">
            <img src="/wp-content/uploads/2016/05/ico-health.jpg" alt="Health" title="Health">
          </div>
          <div class="three columns seen col3">
            <img src="/wp-content/uploads/2016/05/ico-instyle.jpg" alt="InStyle" title="InStyle">
          </div>
          <div class="three columns seen col4">
            <img src="/wp-content/uploads/2016/05/ico-idea.jpg" alt="Idea Health & Fitness Association" title="Idea Health & Fitness Association">
          </div>
        </div>
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