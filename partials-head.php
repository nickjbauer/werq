<?php

//feat. image
if (has_post_thumbnail()) {
  the_post_thumbnail('full');
}

//video for homepage
if (!empty(get_post_meta(get_the_ID(),'youtube_video_id',true))) { ?>
  <div class="videoWrapper">
    <iframe  src="https://www.youtube.com/embed/<?=get_post_meta(get_the_ID(),'youtube_video_id',true)?>" frameborder="0" allowfullscreen></iframe>
  </div>
  <?
}