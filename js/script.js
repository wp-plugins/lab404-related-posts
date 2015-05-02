function UpdateRelatedPostsShortcode(){
    var related_posts_template = jQuery("#related_posts_template").val();
    var related_article_number = jQuery("#related_article_number").val();
    var related_article_cols = jQuery("#related_article_cols").val();
    var order_by = jQuery("#order_by").val();
    var order_way = jQuery("#order_way").val();
    var tag_related = jQuery('#tags_related').is(':checked'); 
    tag_related = (tag_related) ? '1' : '0';
    var category_related = jQuery('#category_related').is(':checked'); 
    category_related = (category_related) ? '1' : '0';
    var complete_code = '[lab404-related-posts template="'+related_posts_template+'" limit="'+related_article_number+'" cols="'+related_article_cols+'" related_by_tag="'+tag_related+'" related_by_category="'+category_related+'" order_by="'+order_by+'" order="'+order_way+'"]';
    jQuery("#shortcode_area_div").text(complete_code);

};

