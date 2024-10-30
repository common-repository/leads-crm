<form method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">
	<?php wp_nonce_field( 'zleadscrm_add_interaction' ); ?>
    <input type="hidden" name="action" value="zleadscrm_add_interaction"/>
    <input type="hidden" name="lead_id" value="<?php echo $_GET['edit_lead']; ?>"/>
    <div class="zleadscrm_row zleadscrm_col_12" style="vertical-align: top">
        <div class="zleadscrm_row zleadscrm_col_12 zleadscrm_disabled_padding"
             style="border-bottom: solid 1px #bbb;  margin-bottom: 1rem;">
            <label for="zleadscrm_interaction_message"
                   class="zleadscrm_col_12 zleadscrm_disabled_padding zleadscrm_form_label"
                   style="font-size: 14px;"><b>Notes</b></label>
        </div>
        <textarea class="zleadscrm_col_12" id="zleadscrm_interaction_message"
                  name="zleadscrm_interaction_message"
                  cols="12" rows="5" required="required"></textarea>
        <div class="zleadscrm_col_12 zleadscrm_disabled_padding"
             style="text-align: right; align-self: start; margin-top:10px;">
            <input class="button" type="submit" value="Add" style="padding: 0 2rem;">
        </div>
    </div>
</form>