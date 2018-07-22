<div class="twelve columns">
  <?php
    switch(get_the_ID ()):
    case 39: //search
  ?>
    <article>
      <?php the_content(); ?>
    </article>

    <div class="search_forms_container">
      <form action="/find-a-class/find-a-cass-list/" method="get">
        <input type="text" placeholder="Search by instructor" name="instructor" maxlength="50">
        <input type="submit" value="">
      </form>

      <form action="/find-a-class/find-a-cass-list/" method="get">
        <input type="text" placeholder="Search by city" name="city" maxlength="50">
        <input type="submit" value="">
      </form>

      <form action="/find-a-class/find-a-cass-list/" method="get">
        <select name="state">
          <option value="">Select A State</option>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </select>
        <input type="submit" value="">
      </form>

      <form action="/find-a-class/find-a-cass-list/" method="get">
        <input type="text" placeholder="Search by zip code" name="zip" maxlength="5">
        <input type="submit" value="">
      </form>
    </div>

  <?php
      break;
      case 5968: //list

        $city = (isset($_GET['city'])) ? $_GET['city'] : null;
        $state = (isset($_GET['state'])) ? $_GET['state'] : null ;
        $zip = (isset($_GET['zip']))? $_GET['zip'] : '';
        $instructor = (isset($_GET['instructor']))? $_GET['instructor'] : '';

        $distance_sql = ', null as distance';

        if (!empty($city)) {
          $type = 'CITY';
          $city = substr(trim(filter_var($city, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_LOW])), 0, 25);

          //minimum of 3 chars
          if (strlen($city) < 3) {
            $city = '## NO DB RUN ##';
          }

        } elseif (!empty($instructor)) {
          $type = 'INSTRUCTOR';
          $instructor = trim(filter_var($instructor, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_LOW]));

        } elseif (!empty($zip)) {
          $type = 'ZIP';
          $zip = (int) substr(filter_var($zip, FILTER_SANITIZE_NUMBER_INT),0,10);


            //get zip cordinates
          $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);
          $sql = "
              select
              latitude
              , longitude
              from zip_code where zipcode = ?
              limit 1
            ";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("d", $zip);
            $stmt->execute();
            $stmt->bind_result($lat, $lng);
            $stmt->store_result();
            $stmt->fetch();

            if ($stmt->num_rows <= 0) {
              $lat = 0;
              $lng = 0;
            }

            $distance_sql = "
            , ( 3959 * acos( cos( radians(?) ) * cos( radians( z.latitude ) )
* cos( radians( z.longitude ) - radians(?) ) + sin( radians(?) ) * sin(radians(z.latitude)) ) ) AS distance
            ";

          } else {
            $type = 'STATE';
            $state = strtoupper(filter_var(substr(trim($state),0,2), FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW]));
          }

  ?>
      <article>
      <div class="row find_a_class_list">
        <div class="twelve columns">
          <h2>Search Results</h2>
          <h3>Search results are listed in order of closest locations, then by the day of the week.</h3>
          <?php
            //execute sql based on command
            $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);

            $sql = "
              select distinct
                  c.id
                  , u.fname
                  , u.lname
                  , c.gym_name
                  , c.gym_address
                  , c.gym_city
                  , c.gym_state
                  , c.gym_zip
                  , c.day
                  , c.time
                  , case c.day
                      when 'MON' then 1
                      when 'TUR' then 2
                      when 'WED' then 3
                      when 'THU' then 4
                      when 'FRI' then 5
                      when 'SAT' then 6
                      when 'SUN' then 7
                      else 8
                    end as day_order
                  ".$distance_sql."
              from ".MY_MEMBER_DB_TABLE." u
              inner join ".MY_MEMBER_CLASS_DB_TABLE." c on u.id = c.user_id
              inner join ".MY_ZIP_DB_TABLE." z on c.gym_zip = z.zipcode
              where u.status = 1
            ";
            //	--and (z.Latitude BETWEEN ?-2 and ?+2) and (z.Longitude BETWEEN ?-2 and ?+2)

            //day filter
            $day = (isset($_GET['day']))? $_GET['day'] : 'NO DAY FILTER';

            if (!is_null($day)) {
              $day = strtoupper(substr(trim(filter_var($day, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW])),0,3));
              $day_s = '%'.$day.'%';
            }

            switch($day) {
              case 'SUN': $sql .= " and c.day like ?"; break;
              case 'MON': $sql .= " and c.day like ?"; break;
              case 'TUE': $sql .= " and c.day like ?"; break;
              case 'WED': $sql .= " and c.day like ?"; break;
              case 'THU': $sql .= " and c.day like ?"; break;
              case 'FRI': $sql .= " and c.day like ?"; break;
              case 'SAT': $sql .= " and c.day like ?"; break;
              default: $sql .= " and c.day <> ?"; break;
            }


            //append based on filter
            switch($type) {
              case 'STATE':
                $sql .= ' and c.gym_state = ? ORDER BY day_order, c.time, c.gym_name';
                break;

              case 'CITY':
                $sql .= " and c.gym_city like ? ORDER BY day_order, c.time, c.gym_name";
                break;

              case 'INSTRUCTOR':
                $sql .= " and (u.fname like ? or u.lname like ?) order by u.lname, day_order, c.time, c.gym_name";
                break;

              default:
                $sql .= "
                  and (z.Latitude BETWEEN ?-2 and ?+2) and (z.Longitude BETWEEN ?-2 and ?+2)
                  having distance < 75
                  order by distance, day_order, c.time, c.gym_name
                ";
            }
            //pqd($sql);

            $stmt = $con->prepare($sql);

            //append based on filter
            switch($type) {
              case 'STATE':
                $stmt->bind_param("ss", $day_s, $state);
                break;

              case 'CITY':
                $city = '%'.$city.'%';
                $stmt->bind_param("ss", $day_s, $city);
                break;

              case 'INSTRUCTOR':
                $instructor = '%'.$instructor.'%';
                $stmt->bind_param("sss", $day_s, $instructor, $instructor);
                break;

              default:
                $stmt->bind_param("dddsdddd", $lat,$lng,$lat,$day_s,$lat,$lat,$lng,$lng);
            }

            $stmt->execute();
            $stmt->bind_result($id, $fname, $lname, $gym, $gym_address, $gym_city, $gym_state, $gym_zip,$day,$time, $day_order, $distance);
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
          ?>
              <form action="/find-a-class/find-a-cass-list/" class="filter_form day" method="get">
                <label>Filter by Day:</label>
                <select name="day" onchange="this.form.submit()">
                  <option value="">No Filter</option>
                  <option value="SUN" <?=($day=='SUN')?'selected':''?>>Sunday</option>
                  <option value="MON" <?=($day=='MON')?'selected':''?>>Monday</option>
                  <option value="TUE" <?=($day=='TUE')?'selected':''?>>Tuesday</option>
                  <option value="WED" <?=($day=='WED')?'selected':''?>>Wednesday</option>
                  <option value="THU" <?=($day=='THU')?'selected':''?>>Thursday</option>
                  <option value="FRI" <?=($day=='FRI')?'selected':''?>>Friday</option>
                  <option value="SAT" <?=($day=='SAT')?'selected':''?>>Saturday</option>
                </select>
                <input type="hidden" name="zip" value="<?=$zip?>">
                <input type="hidden" name="instructor" value="<?=$instructor?>">
                <input type="hidden" name="city" value="<?=$city?>">
                <input type="hidden" name="state" value="<?=$state?>">
              </form>

              <table class="event_table find_a_class">
                <tr>
                  <th>Instructor</th>
                  <th>Day</th>
                  <th>Time</th>
                  <th>Gym Name</th>
                  <th>State</th>
                  <th>Address</th>
                  <th>Register</th>
                </tr>
          <?php while ($stmt->fetch()): ?>
                <tr>
                  <td data-label="<?= (!empty($fname) || !empty($lname)) ? 'Instructor:&nbsp;' : ''; ?>"><?= (!empty($fname) || !empty($lname)) ? $lname.', '.$fname : ''; ?></td>
                  <td data-label="<?= (!empty($day)) ? 'Day:&nbsp;' : ''; ?>"><?= (!empty($day)) ? $day : ''; ?></td>
                  <td data-label="<?= (!empty($time)) ? 'Time:&nbsp;' : ''; ?>"><?= (!empty($time)) ? $time : ''; ?></td>
                  <td data-label="<?= (!empty($gym)) ? 'Gym:&nbsp;' : ''; ?>"><?= (!empty($gym)) ? $gym : ''; ?></td>
                  <td data-label="<?= (!empty($gym_state)) ? 'State:&nbsp;' : ''; ?>"><?= (!empty($gym_state)) ? $gym_state : ''; ?></td>
                  <td data-label="<?= (!empty($gym_address) || !empty($gym_city) || !empty($gym_state) || !empty($gym_zip) ) ? 'Address:&nbsp;' : ''; ?>"><?= (!empty($gym_address))? $gym_address.'<span class="no_mobile_break">,&nbsp;</span>'.$gym_city.', '.$gym_state.' '.$gym_zip : $gym_city.', '.$gym_state.' '.$gym_zip; ?></td>
                  <td data-label=""><?= (!empty($id)) ? '<a href="/find-a-class-detail/?id=' . encrypt_decrypt_api('encrypt',$id) . '"><div class="register">More Info</div></a>' : ''; ?></td>
                </tr>
          <?php endwhile; ?>
                </table>

          <?php
            } else { echo '<h2>WERQ has not yet reached your area.  <a href="/bring-werq-to-your-gym/">Bring WERQ to You.</a></h2>'; }
          ?>
        </div>
      </div>
      </article>

      <article>
      <div class="row find_a_class_list">
        <div class="twelve columns">
          <h2>More Results:</h2>
          <h3>The following are nearby instructors who want to start new classes:</h3>
          <?php
          try {
            //execute sql based on command
            $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);
            $sql = "
              select distinct
                  c.id
                  , u.fname
                  , u.lname
                  , u.state
                  , u.zip
                  ".$distance_sql."
              from ".MY_MEMBER_DB_TABLE." u
              inner join ".MY_MEMBER_CLASS_DB_TABLE." c on u.id = c.user_id
              inner join ".MY_ZIP_DB_TABLE." z on u.zip = z.zipcode
              where u.status = 1
            ";
            //append based on filter
            switch($type) {
              case 'STATE':
                $sql .= ' and u.state = ?';
                break;

              case 'CITY':
                $sql .= " and u.city like ?";
                break;

              case 'INSTRUCTOR':
                $sql .= " and (u.fname like ? or u.lname like ?) order by u.lname";
                break;

              default:
                $sql .= "
                  and (z.Latitude BETWEEN ?-2 and ?+2) and (z.Longitude BETWEEN ?-2 and ?+2)
                  having distance < 75
                  order by distance
                ";
            }
            $stmt = $con->prepare($sql);

            //append based on filter
            switch($type) {
              case 'STATE':
                $stmt->bind_param("s", $state);
                break;

              case 'CITY':
                $city = '%'.$city.'%';
                $stmt->bind_param("s", $city);
                break;

              case 'INSTRUCTOR':
                $instructor = '%'.$instructor.'%';
                $stmt->bind_param("ss", $instructor, $instructor);
                break;

              default:
                $stmt->bind_param("ddddddd", $lat,$lng,$lat,$lat,$lat,$lng,$lng);
            }

            $stmt->execute();
            $stmt->bind_result($id, $fname, $lname, $instructor_state, $instructor_zip, $distance);
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
          ?>
              <table class="event_table find_a_class">
                <tr>
                  <th>Instructor</th>
                  <th>State</th>
                  <th>Zipcode</th>
                  <th>Register</th>
                </tr>
          <?php while ($stmt->fetch()): ?>
                <tr>
                  <td data-label="<?= (!empty($fname) || !empty($lname)) ? 'Instructor:&nbsp;' : ''; ?>"><?= (!empty($fname) || !empty($lname)) ? $lname.', '.$fname : ''; ?></td>
                  <td data-label="<?= (!empty($instructor_state)) ? 'State:&nbsp;' : ''; ?>"><?= (!empty($instructor_state)) ? $instructor_state : ''; ?></td>
                  <td data-label="<?= (!empty($instructor_zip)) ? 'Zipcode:&nbsp;' : ''; ?>"><?= (!empty($instructor_zip)) ? $instructor_zip : ''; ?></td>
                  <td data-label=""><?= (!empty($id)) ? '<a href="/find-a-class-detail/?id=' . encrypt_decrypt_api('encrypt',$id) . '"><div class="register">More Info</div></a>' : ''; ?></td>
                </tr>
          <?php endwhile; ?>
                </table>

          <?php
        } else { echo '<h2>No intructors found nearby.  Call our staff to get help you get to WERQ!</h2>'; }
          } catch (Exception $e) {
            // do nothing.  ideally we'd report this to the webadmin, but for now...
            var_dump($e);
          }
          ?>

        </div>
      </div>
    </article>

  <?php
      break;
      case 5970: //detail
        $id = (int) encrypt_decrypt_api('decrypt',$_GET['id']);
        $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);
        $sql = "
          select
            u.fname
            , u.lname
            , u.email
            , u.avatar
            , u.website
            , u.bio
            , c.gym_name
            , c.gym_address
            , c.gym_city
            , c.gym_state
            , c.gym_zip
            , c.gym_notes
            , c.day
            , c.time
          from ".MY_MEMBER_CLASS_DB_TABLE." c
          inner join ".MY_MEMBER_DB_TABLE." u on c.user_id = u.id
          where c.id = ?
          limit 1
        ";

      $stmt = $con->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->bind_result($fname, $lname, $instructor_email, $avatar, $website, $bio, $gym, $gym_address, $gym_city, $gym_state, $gym_zip, $gym_notes, $day,$time);
      $stmt->store_result();
    ?>
      <article>
      <div class="row find_a_class_detail">
    <?php
      if ($stmt->num_rows > 0) {
        $stmt->fetch();
    ?>
          <div class="six columns">
            <div class="google-maps">
              <iframe src="https://maps.google.com/maps?f=q&hl=en&geocode=&q= <?=$gym_address?> <?=$gym_city?>, <?=$gym_state?> <?=$gym_zip?>&ie=UTF8&z=14&output=embed" width="600" height="450" frameborder="0"></iframe>
            </div>
            <div class="gym_details">
              <p><strong><?= (!empty($gym)) ? $gym : ''; ?></strong></p>
              <p><?= (!empty($gym_address))? $gym_address.'<br>'.$gym_city.', '.$gym_state.' '.$gym_zip : $gym_city.', '.$gym_state.' '.$gym_zip; ?></p>
              <p><?=$day?> | <?=$time?></p>
            </div>
            <?php if (!empty($gym_notes)) { ?>
              <div class="gym_notes">
                <label>Gym Notes:</label>
                <p><?=$gym_notes?></p>
              </div>
            <?php } ?>

            <!-- gym notes will be here -->

            <div class="back_link">
              <a href="javascript:history.back();">« Back</a>
            </div>
          </div>

          <div class="five columns offset-by-one">
            <div class="avatar"><img src="<?= (!empty($avatar))? '//members.werqfitness.com/uploads/'.$avatar:'/wp-content/uploads/2016/04/Original_RGB_800px-1-300x188.jpg' ?>"></div>
            <div class="instructor_detail">
              <strong>Instructor:</strong> <?=$fname?> <?=$lname?>
            </div>
            <div class="instructor_contact">
              <a href="mailto:<?=my_email_scrambler($instructor_email)?>,info[wat2]werqfitness.com?subject=WERQ Fitness - a message from your profile">Contact Instructor</a>
              <?php //echo do_shortcode('[contact-form-7 id="5981" title="Instructor Form"]')?>
            </div>
            <div class="instructor_bio">
              <p><?=$bio?></p>
            </div>
            <?php
              if (!empty($website)):
                if (stripos($website,'http://') === false) {
                  $website = 'http://'.$website;
                }
            ?>
              <p><a href="<?=$website?>" target="_blank"><?=$website?></a></p>
            <?php endif; ?>
          </div>
    <?php
      } else {
    ?>
        <div class="tweleve columns not_found">
          <h2>Sorry No Profile Found</h2>
          <p><a href="/find-a-class/">Try another search</a></p>
        </div>
  <?php
      }
  ?>
      </div>
      </article>
  <?php
    break;
  endswitch;
  ?>

</div>

<script>var maildivider="[wat]";for(i=0;i<=document.links.length-1;i++)-1!=document.links[i].href.indexOf(maildivider)&&(document.links[i].href=document.links[i].href.split(maildivider)[0]+"@"+document.links[i].href.split(maildivider)[1]);var maildivider="[wat2]";for(i=0;i<=document.links.length-1;i++)-1!=document.links[i].href.indexOf(maildivider)&&(document.links[i].href=document.links[i].href.split(maildivider)[0]+"@"+document.links[i].href.split(maildivider)[1]);</script>