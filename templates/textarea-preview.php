<div class="TAP-wrapper" id="<?=$previewWrapperDomID?>" data-ajax-url="<?=$ajaxUrl?>" data-field-name="<?=$fieldName?>">
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