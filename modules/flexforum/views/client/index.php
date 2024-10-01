<div class="container">
    <div class="jumbotron flexforum-search-jumbotron">
        <div class="flexforum-search">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="text-center">
                            <?php echo form_open(current_url(), ['method' => 'GET', 'id' => 'flexforum-search-form']); ?>
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="input-group">
                                    <input type="search" name="q"
                                        placeholder="<?php echo _l('flexforum_search_placeholder'); ?>"
                                        class="form-control flexforum-search-input"
                                        value="<?php echo $this->input->get('q'); ?>">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success flexforum-search-button">
                                            <?php echo _l('flexforum_search'); ?>
                                        </button>
                                    </span>
                                    <i
                                        class="fa-solid fa-magnifying-glass form-control-feedback flexforum-search-icon"></i>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo flexforum_get_topics_partial() ?>
</div>