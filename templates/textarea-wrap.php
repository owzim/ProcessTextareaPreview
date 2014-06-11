<div class="TAP-wrapper" id="<?=$previewWrapperDomID?>">
    <div class="TAP-field"><?=$fieldOutput?></div>
    <div class="TAP-preview">
        <iframe src="" class="TAP-previewContent" id="<?=$iframeDomID?>"></iframe>
    </div>
</div>

<script>
    (function() {
        owzim.TextareaPreview.initField(
            $("#<?=$textareaDomID?>"), $("#<?=$textareaWrapperDomID?>"),
            $("#<?=$previewWrapperDomID?>"), $("#<?=$iframeDomID?>"),
            <?=$params?>
        );
    })();
</script>