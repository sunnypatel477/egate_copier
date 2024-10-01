<div class="ff-statement-wrapper center ff-thankyou-wrapper">
    <?php echo $this->load->view('partials/cover', ['block' => $block], true); ?>
    <div class="preview-statement-title tw-mb-4">
        <?php echo $this->load->view('partials/title-label', ['block' => $block], true); ?>
        <?php echo $this->load->view('partials/description-label', ['block' => $block], true); ?>
        <?php if($block['redirect_url']): ?>
        <?php
            $url = $block['redirect_url'];
           //append https if not present
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "https://" . $url;
            }
        ?>
            <div class="tw-mt-4 ff-thankyou-wrapper-redirect-block">
                <p><i class="fa-solid fa-spinner"></i></p>
                <p><?php echo $block['redirect_message'] ?></p>
            </div>
        <?php if(!$preview): ?>
            <script>
                setTimeout(function() {
                    window.location.replace('<?php echo $url ?>');
                }, <?php echo (int)$block['redirect_delay'] * 1000 ?>);
            </script>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>