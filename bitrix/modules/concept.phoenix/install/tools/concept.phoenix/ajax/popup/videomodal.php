<?$VideoCode = htmlspecialchars($_POST["arParams"]["element_id"]);?>

<div class="shadow-modal"></div>

<div class="phoenix-modal video-modal">

	<div class="phoenix-modal-dialog">
		
		<div class="dialog-content">
            <a class="close-modal"></a>

            <div class="content-in">
                <iframe allowfullscreen="" frameborder="0" height="100%" src="https://www.youtube.com/embed/<?=$VideoCode?>?rel=0&amp;controls=1&amp;showinfo=0&amp;autoplay=1" width="100%"></iframe>
            </div>

        </div>

	</div>
</div>