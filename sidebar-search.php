<div class="right sidebar">

    <?php dynamic_sidebar('search_sidebar'); ?>

    <div style="padding: 30px 0; background: none;">
        <div id="sidebar-search" class="search scrollme animateme"
             data-when="span"
             data-from="1"
             data-to="0.5"
             easing="easein"
             data-opacity="0"
            >

            <form class="search" method="get" action="/szukaj">
                <input type="text" name="search" id="search" placeholder="Szukaj..">
                <button type="submit" class="btn"><i class="fas fa-search"></i></button>
            </form>

        </div>
    </div>


</div>
