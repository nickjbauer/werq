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
        <input type="text" placeholder="Search by city" name="city">
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
        <input type="text" placeholder="Search by zip code" name="zip">
        <input type="submit" value="">
      </form>
    </div>

  <?php
      break;
      case 5968: //list

        $city = (isset($_GET['city'])) ? $_GET['city'] : null;
        $state = (isset($_GET['state'])) ? $_GET['state'] : null ;
        $zip = (isset($_GET['zip']))? $_GET['zip'] : null;

        if (!empty($city)) {
          $type = 'CITY';
          $city = substr(trim(filter_var($city, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW])),0,25);
        } elseif (!empty($zip)) {
          $type = 'ZIP';
          $zip = substr(filter_var($zip, FILTER_SANITIZE_NUMBER_INT),0,10);
        } else {
          $type = 'STATE';
          $state = strtoupper(filter_var(substr(trim($state),0,2), FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW]));
        }
  ?>
  <div class="row">
    <div class="twelve columns">
      <table class="event_table find_a_class">
        <tr>
          <th>Day</th>
          <th>Time</th>
          <th>Gym Name</th>
          <th>State</th>
          <th>Address</th>
          <th>Register</th>
        </tr>
        <?php
        //execute sql based on command
        $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);

        $sql = "
          select distinct
              u.id
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
          from ".MY_MEMBER_DB_TABLE." u
          inner join ".MY_MEMBER_CLASS_DB_TABLE." c on u.id = c.user_id
          inner join ".MY_ZIP_DB_TABLE." z on c.gym_zip = z.zipcode
          where u.status = 1
        ";
        //	--and (z.Latitude BETWEEN ?-2 and ?+2) and (z.Longitude BETWEEN ?-2 and ?+2)

        //append based on filter
        switch($type) {
          case 'STATE':
            $sql .= ' and c.gym_state = ?';
            break;

          case 'CITY':
            $sql .= " and c.gym_city like ?";
            break;

          default:

        }

        //order by clause
        $sql .= " ORDER BY day_order, c.time, c.gym_name";

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

          default:

        }

        $stmt->execute();
        $stmt->bind_result($id, $fname, $lname, $gym, $gym_address, $gym_city, $gym_state, $gym_zip,$day,$time, $day_order);
        $stmt->store_result();
        if ($results > 0) {
          while ($stmt->fetch()) { ?>
            <tr>
              <td><?= (!empty($day)) ? $day : ''; ?></td>
              <td><?= (!empty($time)) ? $time : ''; ?></td>
              <td><?= (!empty($gym)) ? $gym : ''; ?></td>
              <td><?= (!empty($state)) ? $gym_state : ''; ?></td>
              <td><?= (!empty($gym_address))? $gym_address.'<br>'.$gym_city.', '.$gym_state.' '.$gym_zip : $gym_city.', '.$gym_state.' '.$gym_zip; ?></td>
              <td><?= (!empty($id)) ? '<a href="/find-a-class-detail/?id=' . $id . '"><div class="register">More Info</div></a>' : ''; ?></td>
            </tr>
      <?php
          }
        } else { echo '<tr><td colspan="6" style="text-align: center"><h2>Sorry No Results Found</h2></td>'; }
      ?>
      </table>
    </div>
  </div>


  <?php
      break;
      case 5970: //detail
        $id = $_GET['id'];

        echo $id;
  ?>

  this is 6970



  <?php
    break;
  endswitch;
  ?>

</div>