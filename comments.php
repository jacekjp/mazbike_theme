
<?php if ( post_password_required() ) { return; } ?>
<div id="comments" class="comments-area">
    <?php if ( have_comments() ) { ?>
        <h4 class="comments-title">
            <?php $commentsNumber = get_comments_number(); echo $commentsNumber . ' ' .  polishPlural('Komentarz', 'Komentarze', 'Komentarzy', $commentsNumber);  ?>
        </h4>
        <span class="title-line"></span>
        <ol class="comment-list">
            <?php wp_list_comments( array( 'avatar_size' => 70, 'style' => 'ul', 'callback' => 'mazbike_comments', 'type' => 'all' ) ); ?>
        </ol>
        <?php the_comments_pagination( array( 'prev_text' => '<i class="fa fa-angle-left" aria-hidden="true"></i> <span class="screen-reader-text">' . __( 'Previous', 'rowerem-po-mazowszu') . '</span>', 'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'rowerem-po-mazowszu') . '</span> <i class="fa fa-angle-right" aria-hidden="true"></i>', ) ); ?>
    <?php } ?>
    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'rowerem-po-mazowszu'); ?></p>
    <?php } ?>
    <?php comment_form(); ?>
</div>


    