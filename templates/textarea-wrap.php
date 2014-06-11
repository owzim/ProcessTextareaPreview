<div class="TAP-wrapper" id="<?php echo $previewWrapperDomID ?>">
    <div class="TAP-field"><?php echo $fieldOutput ?></div>
    <div class="TAP-preview">
        <iframe src="" class="TAP-previewContent" id="<?php echo $iframeDomID ?>"></iframe>
    </div>
</div>

<script>
    (function() {
        owzim.TextareaPreview.initField(
            $("#<?php echo $textareaDomID ?>"), $("#<?php echo $textareaWrapperDomID ?>"),
            $("#<?php echo $previewWrapperDomID ?>"), $("#<?php echo $iframeDomID ?>"),
            <?php echo $params ?>
        );
    })();
</script>