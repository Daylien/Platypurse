<?php
use Hydro\Helper\CacheBuster;

$isSubPage = false;
if(isset($_GET['url'])){
    $slashPos = strpos($_GET['url'], '/', 1);
    if ($slashPos) {
        $isSubPage = true;
    }
}

?>

<footer>
    <div class="footer-link-container">
        <a href="./contact">Kontakt</a>
        <a href="./legalNotice">Impressum</a>
        <a href="./dataPrivacy">Datenschutzerklärung</a>
        <a href="./termsOfUse">Nutzungsbedingungen</a>
        <a href="https://uol.de/" target="_blank">Universität<br>Oldenburg</a>
    </div>
    <div class="footer-copyright-container">
        <img src="<?= CacheBuster::serve("assets/logo/svg/logo_1tone.svg", $isSubPage) ?>" alt="footer-logo">
        <p class="copyright">&copy; 2020 Platypurse</p>
        <p class="designed-for">Projekt für das Modul "Webprogrammierung" an der Carl&nbsp;von&nbsp;Ossietzky&nbsp;Universität&nbsp;Oldenburg</p>
        <p class="designed-by">
            Gestaltet von <em>
                <a href="https://github.com/Daylien" target="_blank">Malte&nbsp;Grave</a>,
                <a href="https://github.com/derPiepmatz" target="_blank">Tim&nbsp;Hesse</a> &
                <a href="https://github.com/Ceitcher" target="_blank">Marvin&nbsp;Kuhlmann</a>
            </em>
        </p>
    </div>
    <div class="footer-light-mode-switch-container no-js-hide" id="light-mode-switch">
        <button title="Licht an" id="light-mode-switch-on">
            <span class="fas fa-sun"></span>
        </button>
        <button title="Licht aus" id="light-mode-switch-off" hidden>
            <span class="fas fa-moon"></span>
        </button>
    </div>
</footer>
</body>
</html>
