<h2><?php echo $related_posts_title;?></h2>
<div class="lab404_list_container">
    <?php if ($cat_posts): ?>
    <?php foreach($cat_posts as $one_post): ?>
    <?php 
    /*
     *  Get article image, first try to get featured image, and then first image from post
     *  Return no-image placeholder if can't get image from any source
     */
    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($one_post->ID), 'full' ); 
    $thumbnail = $thumbnail[0];
    if(!$thumbnail){
        $thumbnail = $this->api()->GetFirstImageFromPost($one_post->post_content);
    }
    ?>
  	<div class="lab404_list_item">
            <a href='<?php echo get_permalink($one_post->ID); ?>'>
                <img align="left" class="lab404_list_item_image" src="<?php echo $thumbnail;?>" />
                <?php echo $one_post->post_title;?>
            </a>
            <br>
            <span class="lab404_list_item_meta"><?php echo get_the_author_meta( 'user_nicename', $one_post->post_author ); ?> @ <?php echo $one_post->post_date;?></span>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>