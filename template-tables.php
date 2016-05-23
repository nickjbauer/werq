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

      while (have_posts()) : the_post();
        ?>
        <article>
          <?php
          if (get_the_ID() == 41) { //contact
            ?>
            <table class="event_table teach">
              <tr>
                <th>Date & Time</th>
                <th>Gym Name</th>
                <th>State</th>
                <th>Address</th>
                <th>Register</th>
              </tr>

              <?
              wp_reset_query();

              $args = array(

                'post_type' 		=> 'teach-werq',
                'posts_per_page' 	=> -1,
                'meta_key' => 'wpcf-teach-end-date',
                'orderby' => 'meta_value',
                'order' => 'DESC'
              );

              $loop = new WP_Query($args);

              while ( $loop->have_posts() ) {
                $loop->the_post();

                $id = get_the_ID();
                $title = get_the_title();
                $gym = get_post_meta($id, 'wpcf-teach-gym', true);
                $address = get_post_meta($id, 'wpcf-teach-address', true);
                $state = get_post_meta($id, 'wpcf-teach-state', true);
                $url = get_post_meta($id, 'wpcf-teach-url', true);

                $date_output_str = my_output_date(get_post_meta($id, 'wpcf-teach-start-date', true),get_post_meta($id, 'wpcf-teach-end-date', true));

                //echo get_the_title().'<br>';
                //echo date('D, F jS Y g:i A',get_post_meta($id, 'wpcf-teach-start-date', true)).'<br>';
                //echo date('D, F jS Y g:i A',get_post_meta($id, 'wpcf-teach-end-date', true)).'<br>';
                //echo $start_date.' '.$start_time.' - '.$end_date.' '.$end_time.'<br>';
                //echo get_post_meta($id, 'wpcf-teach-address', true).'<br>';
                //echo get_post_meta($id, 'wpcf-teach-state', true).'<br>';
                //echo get_post_meta($id, 'wpcf-teach-url', true).'<br>';
                //echo $date_output_str.'<br>';
                //echo '<hr>';
                ?>
                <tr>
                  <td><?= (!empty($date_output_str))? $date_output_str : ''; ?></td>
                  <td><?= (!empty($gym))? $gym : ''; ?></td>
                  <td><?= (!empty($state))? $state : ''; ?></td>
                  <td><?= (!empty($address))? $address : ''; ?></td>
                  <td><?= (!empty($url))? '<a href="'.$url.'" target="_blank"><div class="register">Register Now</div></a>' : ''; ?></td>
                </tr>
                <?php

              }
              ?>
            </table>

            <h2><a href="#">##Request a WERQ Certification in your area##</a></h2>
            <h2><a href="#">##Certification FAQs##</a></h2>
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