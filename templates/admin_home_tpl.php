<div class="wrap">
    <h1>Related Posts Options</h1>

    <div class="postbox">
        <div class="inside">
            <h3>Related articles settings</h3>
            <div class="menu-settings">
                <form action="admin.php?page=lab404_related_posts&action=save_options" method="post">
                <?php wp_nonce_field( 'lab404-related-posts-options' ); ?>
                <table width="100%">
                    <tr>
                        <td>
                            <dl>
                                <dt class="howto">Enable related posts below article? </dt>
                                <dd class="checkbox-input">
                                    <input type="radio" name="enable_related_posts" <?php if($enable_related_posts):?>checked<?php endif;?> id="enable_related_posts" value="1"> 
                                    <label for="enable_related_posts">Enable</label>
                                </dd>
                                <dd class="checkbox-input">
                                    <input type="radio" name="enable_related_posts" <?php if(!$enable_related_posts):?>checked<?php endif;?> id="disable_related_posts" value="0"> 
                                    <label for="disable_related_posts">Disable</label>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <dl>
                                <dt class="howto">How article should be related</dt>
                                <dd class="checkbox-input">
                                    <input type="checkbox" onchange="UpdateRelatedPostsShortcode();" <?php if($tags_related === "1"):?>checked<?php endif;?>  name="tags_related" id="tags_related" value="1"> 
                                    <label for="tags_related">Show posts with the same tags</label>
                                </dd>
                                <dd class="checkbox-input">
                                    <input type="checkbox" onchange="UpdateRelatedPostsShortcode();" <?php if($category_related === "1"):?>checked<?php endif;?>  name="category_related" id="category_related" value="1"> 
                                    <label for="category_related">Show posts from the same category</label>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="menu-name-label howto open-label" for="related_posts_template">
                                <span>Related posts template?</span>
                                <select name="related_posts_template" id="related_posts_template" onchange="UpdateRelatedPostsShortcode();">
                                    <option <?php if($related_posts_template === 'boxes'):?>selected<?php endif;?> value="boxes">Article boxes template</option>
                                    <option <?php if($related_posts_template === 'list'):?>selected<?php endif;?> value="list">List of articles template</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="menu-name-label howto open-label" for="menu-name">
                                <span>Related article widget title:</span>
                                <input name="related_article_widget_title" onchange="UpdateRelatedPostsShortcode();" value="<?php echo $related_article_widget_title;?>" id="related_article_widget_title" type="text" class="regular-text">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="menu-name-label howto open-label" for="menu-name">
                                <span>How many articles you want to show?</span>
                                <input name="related_article_number" onchange="UpdateRelatedPostsShortcode();" value="<?php echo $related_article_number;?>" id="related_article_number" type="number" class="menu-name regular-text menu-item-textbox">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="menu-name-label howto open-label" for="menu-name">
                                <span>Number of columns (only for boxes)?</span>
                                <input name="related_article_cols" onchange="UpdateRelatedPostsShortcode();" value="<?php echo $related_article_cols;?>" id="related_article_cols" type="number" class="menu-name regular-text menu-item-textbox">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="menu-name-label howto open-label" for="menu-name">
                                <span>Order:</span>
                                <select name="order_by" id="order_by" onchange="UpdateRelatedPostsShortcode();">
                                    <option <?php if($order_by === 'ID'):?>selected<?php endif;?> value="ID">ID</option>
                                    <option <?php if($order_by === 'date'):?>selected<?php endif;?> value="date">Date</option>
                                    <option <?php if($order_by === 'title'):?>selected<?php endif;?> value="title">Title</option>
                                    <option <?php if($order_by === 'rand'):?>selected<?php endif;?> value="rand">Random</option>
                                </select>
                                <select name="order_way" id="order_way" onchange="UpdateRelatedPostsShortcode();">
                                    <option <?php if($order_way === 'DESC'):?>selected<?php endif;?> value="DESC">Descending</option>
                                    <option <?php if($order_way === 'ASC'):?>selected<?php endif;?> value="ASC">Ascending</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="save_menu" id="save_menu_header" class="button button-primary menu-save" value="Save Options">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>Live shortcode generator:</h3>
                            <label class="menu-name-label howto open-label" for="menu-name">
                            Shortcode (You can place it anywhere inside post): 
                            </label><br>
                            <div class="shortcode_area_div" id="shortcode_area_div">
                                [lab404-related-posts 
                                template="<?php echo $related_posts_template;?>" 
                                limit="<?php echo $related_article_number;?>" 
                                cols="<?php echo $related_article_cols;?>" 
                                related_by_tag="<?php echo $tags_related;?>" 
                                related_by_category="<?php echo $category_related;?>" 
                                order_by="<?php echo $order_by;?>" 
                                order="<?php echo $order_way;?>"]
                            </div>
                            <small>You don't have to save changes to use shortcode. Just copy the code and paste it in any post you want.</small>
                            <br>
                        </td>
                    </tr>
                    
                    
                </table>
                </form>

            </div>
        </div>
    </div>
    
</div>