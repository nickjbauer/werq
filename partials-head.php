<?php

//feat. image
if (has_post_thumbnail()) {
  the_post_thumbnail('full');
}

//video for homepage
$v = get_post_meta(get_the_ID(),'youtube_video_id',true);
if (!empty($v)) { ?>
  <div class="videoWrapper">
    <iframe  src="https://www.youtube.com/embed/<?=get_post_meta(get_the_ID(),'youtube_video_id',true)?>" frameborder="0" allowfullscreen></iframe>
  </div>
  <?
}