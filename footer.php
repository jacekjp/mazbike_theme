<footer>
    <div>
        <p><img src="https://new.rowerempomazowszu.pl/team/wp-content/themes/mazbike/img/LogoWhite.png" alt="Mazbike" class="logo"></p>

                <?php wp_nav_menu( array( 'theme_location' => 'footer-nav', 'menu_class' => 'footer-menu','name' => 'Footer Menu',
                'menu_id' => 'footer-nav',
                'container' => 'nav' ) ); ?>
        <div>
            <ul>
                <li><a href="https://www.facebook.com/Mazbike/" target="_blank"><i class="fab fa-facebook-square fa-2x"></i></a></li>
                <li><a href="https://www.instagram.com/mazbike/" target="_blank"><i class="fab fa-instagram fa-2x"></i></a></li>
                <li><a href="/feed/" target="_blank"><i class="fas fa-rss-square fa-2x"></i></a></li>
            </ul>
<!--            <p>-->
<!--                <a href="http://zaliczgmine.pl/users/view/455"><img src="http://zaliczgmine.pl/img/buttons/button-455-white.png" /></a>-->
<!--            </p>-->

        </div>
    </div>

    <div><p>Copyright © <?php echo date('Y'); ?> Mazbike</p></div>
</footer>
<a href="#" class="scrollToTop"></a>
<?php wp_footer(); ?>
</body>
</html>