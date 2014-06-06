<div class="TAP-wrapper" id="<?=$previewWrapperDomID?>" data-params='<?=$params?>'>
    <div class="TAP-field"><?=$fieldOutput?></div>
    <div class="TAP-preview">
        <div class="TAP-previewContent" id="<?=$ajaxContentDomID?>">

        </div>
    </div>
</div>

<script>
    (function() {
        var $textarea = $("#<?=$textareaDomID?>"),
            $textareaWrapper = $("#<?=$textareaWrapperDomID?>"),
            $previewWrapper = $("#<?=$previewWrapperDomID?>"),
            $ajaxContent = $("#<?=$ajaxContentDomID?>");

        owzim.TextareaPreview(
            $textarea, $textareaWrapper,
            $previewWrapper, $ajaxContent
        );
    })();
</script>