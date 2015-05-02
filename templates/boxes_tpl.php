<h2><?php echo $related_posts_title;?></h2>
<?php $cols = 0; ?>
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
    
    <?php if ($cols == 0): ?>
        <div class="lab404-related-posts-container">
    <?php endif; ?>
        <div class="tile double">
            <div class="tile-content image">
                <a href='<?php echo get_permalink($one_post->ID); ?>'>
                    <img src='<?php echo $thumbnail;?>'>
                </a>
            </div>
            <div class="brand bg-dark opacity">
                <span class="text">
                    <a target="_blank" href='<?php echo get_permalink($one_post->ID); ?>'>
                        <?php echo $one_post->post_title;?>
                    </a>
                </span>
            </div>
        </div>
    <?php $cols++; ?>
    <?php if ($cols == $defined_cols): ?>
        </div>
        <?php $cols = 0; ?>
    <?php endif; ?>
<?php endforeach; ?>