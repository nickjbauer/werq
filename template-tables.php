<?php
/*
Template Name: Tables
*/

get_header();
?>

  <main role="main">
    <?php
    if (have_posts()) {

      get_template_part( 'partials', 'head' );

      while (have_posts()) {
        the_post()
    ?>
        <article>
          <?php

          the_content();

          switch (get_the_ID()) {
            case 41:
              $post_type = 'teach-werq';
              $slug = 'teach';
              $type = 'TEACH';
              $thead = '
                <th>Date & Time</th>
                <th>Gym Name</th>
                <th>State</th>
                <th>Address</th>
                <th>Register</th>
              ';
              break;
            case 43:
              $post_type = 'event';
              $slug = 'event';
              $type = 'EVENT';
              $thead = '
                <th>Class Title</th>
                <th>Date & Time</th>
                <th>Gym Name</th>
                <th>City</th>
                <th>State</th>
                <th>Register</th>
              ';
              break;
          }
          ?>
          <div class="row">
            <div class="<?=($type=='TEACH')? 'twelve':'nine'?> columns">
              <table class="event_table teach">
                <tr>
                  <?= $thead ?>
                </tr>

                <?php
                wp_reset_query();

                $args = array(
                  'post_type' => $post_type,
                  'posts_per_page' => -1,
                  'meta_key' => 'wpcf-' . $slug . '-end-date',
                  'orderby' => 'meta_value',
                  'order' => 'DESC'
                );

                $loop = new WP_Query($args);

                while ($loop->have_posts()) {
                  $loop->the_post();

                  //get general
                  $id = get_the_ID();
                  $title = get_the_title();
                  $gym = get_post_meta($id, 'wpcf-' . $slug . '-gym', true);
                  $state = get_post_meta($id, 'wpcf-' . $slug . '-state', true);
                  $url = get_post_meta($id, 'wpcf-' . $slug . '-url', true);
                  $date_output_str = my_output_date(get_post_meta($id, 'wpcf-' . $slug . '-start-date', true), get_post_meta($id, 'wpcf-' . $slug . '-end-date', true));
                ?>
                  <tr>
                    <?php
                    switch($type) {
                      case 'TEACH':
                        $address = get_post_meta($id, 'wpcf-' . $slug . '-address', true);
                    ?>
                        <td><?= (!empty($date_output_str)) ? $date_output_str : ''; ?></td>
                        <td><?= (!empty($gym)) ? $gym : ''; ?></td>
                        <td><?= (!empty($state)) ? $state : ''; ?></td>
                        <td><?= (!empty($address)) ? $address : ''; ?></td>
                        <td><?= (!empty($url)) ? '<a href="' . $url . '" target="_blank"><div class="register">Register Now</div></a>' : ''; ?></td>
                    <?
                      break;
                      case 'EVENT':
                        $title = get_the_title();
                        $city = get_post_meta($id, 'wpcf-' . $slug . '-city', true);
                    ?>
                        <td><?= (!empty($title)) ? $title : ''; ?></td>
                        <td><?= (!empty($date_output_str)) ? $date_output_str : ''; ?></td>
                        <td><?= (!empty($gym)) ? $gym : ''; ?></td>
                        <td><?= (!empty($city)) ? $city : ''; ?></td>
                        <td><?= (!empty($state)) ? $state : ''; ?></td>
                        <td><?= (!empty($url)) ? '<a href="' . $url . '" target="_blank"><div class="register">Register Now</div></a>' : ''; ?></td>
                    <?
                      break;
                    }
                    ?>
                  </tr>
                  <?php
                }
                ?>
              </table>
            </div>
            <?php if ($type== 'EVENT') {?>
                <div class="three columns">
                  <aside>
                    <h3>EVENT PHOTO GALLERY</h3>
                    <ul id="event_photo_gallery_listing">
                      <?php
                        wp_reset_query();

                        $args = array(
                          'post_type' => 'event-photo-listing',
                          'posts_per_page' => -1,
                          'order' => 'DESC'
                        );

                        $loop = new WP_Query($args);

                        while ($loop->have_posts()) {
                          $loop->the_post();

                          $id = get_the_ID();
                          $title = get_the_title();
                      ?>
                      <li><a href="/photo-gallery-viewer/?id=<?=$id?>"><?=$title?></a></li>
                    <?php } ?>
                    </ul>
                  </aside>
                </div>
            <?php } ?>
          </div>
          <?php
          //content after table
          if ($type == 'TEACH') {
            ?>
            <h2><a href="#">##Request a WERQ Certification in your area##</a></h2>
            <h2><a href="#">##Certification FAQs##</a></h2>
            <?php
          }
          ?>

        </article>
    <?php
      }
    }
    ?>
  </main>
<?php get_footer(); ?>