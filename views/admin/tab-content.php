<div class="metabox-holder cf7-gs-ext-admin-form">
  <h3><?php _e( 'GetResponse Settings', 'cf7-gr-ext'); ?></h3>
  <fieldset>

    <table class="form-table">
      <tbody>
      	<tr>
        	<th scope="row">
        		<label for="cf7-gs-name"><?php _e( 'Subscriber Name', 'cf7-gr-ext'); ?></label>
            <a href="#" class="cf7-help-icon"><span class="dashicons dashicons-info"></span></a>
        	</th>
        	<td>
        		<!-- <input type="text" id="cf7-gs-name" name="cf7-gs[name]" class="large-text wide" size="70" placeholder="[your-name]" value="<?php echo (isset ($cf7_gs['name'] ) ) ? esc_attr( $cf7_gs['name'] ) : ''; ?>" /> -->
            <select class="field-names large-select" id="cf7-gs-name" name="cf7-gs[name]" data-value="<?php echo (isset ($cf7_gs['name'] ) ) ? esc_attr( $cf7_gs['name'] ) : ''; ?>">
              
            </select>
            <span class="cf7-gs-red-text">*</span>
        	</td>
      	</tr>

        <tr>
        	<th scope="row">
        		<label for="cf7-gs-email"><?php _e( 'Subscriber Email', 'cf7-gr-ext'); ?></label>
            <a href="#" class="cf7-help-icon"><span class="dashicons dashicons-info"></span></a>
        	</th>
        	<td>
        		<!-- <input type="text" id="cf7-gs-email" name="cf7-gs[email]" class="large-text wide" size="70" placeholder="[your-email]" value="<?php echo (isset ( $cf7_gs['email'] ) ) ? esc_attr( $cf7_gs['email'] ) : ''; ?>" /> -->
            
            <select class="field-names large-select" id="cf7-gs-email" name="cf7-gs[email]" data-value="<?php echo (isset ($cf7_gs['email'] ) ) ? esc_attr( $cf7_gs['email'] ) : ''; ?>">
              
            </select>

            <span class="cf7-gs-red-text">*</span>
        	</td>
      	</tr>

        <tr>
        	<th scope="row">
        		<label for="cf7-gs-accept"><?php _e( 'Required Acceptance Field', 'cf7-gr-ext'); ?></label>
            <a href="#" class="cf7-help-icon"><span class="dashicons dashicons-info"></span></a>
        	</th>
        	<td>
        		<!-- <input type="text" id="cf7-gs-accept" name="cf7-gs[accept]" class="large-text wide" size="70" placeholder="[opt-in]" value="<?php echo (isset($cf7_gs['accept'])) ? $cf7_gs['accept'] : '';?>" /> -->
            <select class="field-names large-select"  id="cf7-gs-accept" name="cf7-gs[accept]" data-value="<?php echo (isset ($cf7_gs['accept'] ) ) ? esc_attr( $cf7_gs['accept'] ) : ''; ?>">
              
            </select>

        	</td>
      	</tr>

        <!-- <tr>
        	<th scope="row">
        		<label for="cf7-gs-api"><?php _e( 'GetResponse API Key', 'cf7-gr-ext'); ?></label>
        	</th>
        	<td>
        		<input type="text" id="cf7-gs-api" name="cf7-gs[api]" class="large-text wide" size="70" placeholder="6683ef9bdef6755f8fe686ce53bdf73a-us4" value="<?php echo (isset($cf7_gs['api']) ) ? esc_attr( $cf7_gs['api'] ) : ''; ?>" />
        	</td>
      	</tr> -->

        <tr>
        	<th scope="row">
        		<label for="cf7-gs-list"><?php _e( 'GetResponse Campaign', 'cf7-gr-ext'); ?></label>
            <a href="#" class="cf7-help-icon"><span class="dashicons dashicons-info"></span></a>
        	</th>
        	<td>
            <?php
            $cf7_gs_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );
            if( isset( $cf7_gs_ext_basics_options['gs_con'] ) && false !== $cf7_gs_ext_basics_options['gs_con'] ){
              if( !empty( $cf7_gs_ext_basics_options['gs_camp'] ) ){
            ?>
        		<!-- <input  value="<?php echo (isset( $cf7_gs['list']) ) ?  esc_attr( $cf7_gs['list']) : '' ; ?>" /> -->
            <select id="cf7-gs-list" name="cf7-gs[list]" class="large-select">
              <option value=""><?php _e( 'Select Campaign', 'cf7-gr-ext' ); ?></option>
              <?php
              foreach ($cf7_gs_ext_basics_options['gs_camp'] as $key => $camp ) {
                echo '<option value="'.$key.'" '.( (isset( $cf7_gs['list'] ) && $key == $cf7_gs['list']  )? 'selected':'' ).'>'.$camp->name.'</option>';
              }
              ?>
            </select> <span class="cf7-gs-red-text">*</span> <a href="javascript:void(0);" id="cf7-gs-ext-update-select-camp"><span class="dashicons dashicons-update"></span></a>
            <?php
            }
            else{
              ?>
              <?php _e( 'No Campaign found.', 'cf7-gr-ext' );?>
              <?php
            }
          }
          else{
            ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page='.CF7_GS_EXT_SETTING_SLUG ) ); ?>"><?php _e( 'GetResponse API key is not set or invalid. Click here to change.', 'cf7-gr-ext' ); ?></a>
            <?php
          } ?>
        	</td>
      	</tr>

        <tr>
        	<th scope="row">
        		<label for="cf7-gs-custom-fields"><?php _e( 'Custom Fields', 'cf7-gr-ext'); ?></label>
            <a href="#" class="cf7-help-icon"><span class="dashicons dashicons-info"></span></a>
        	</th>
        	<td>
        		<table class="form-table cf7-gs-custom-fields-tbl">
              <tbody>
                  <?php
                  $count = ( isset($cf7_gs['custom_value'])?count($cf7_gs['custom_value']):1);

                  for($i=1;$i<=$count;$i++){
                    ?>
                <tr data-cfid='<?php echo $i; ?>' class="custom-field-single">

                  <td class="">
                    <label for="cf7-gs-custom-value<?php echo $i; ?>"><?php echo esc_html( __( 'Contact Form Value', 'cf7-gr-ext' ) ); ?></label><br />
                    <!-- <input type="text" id="cf7-gs-custom-value<?php echo $i; ?>" name="cf7-gs[custom_value][<?php echo $i; ?>]" class="large-text" size="20" placeholder="[your-example-value]" value="<?php echo (isset( $cf7_gs['custom_value'][$i]) ) ?  esc_attr( $cf7_gs['custom_value'][$i] ) : '' ;  ?>" /> -->

                    <select class="field-names large-select"  id="cf7-gs-custom-value<?php echo $i; ?>" name="cf7-gs[custom_value][<?php echo $i; ?>]" data-value="<?php echo (isset( $cf7_gs['custom_value'][$i]) ) ?  esc_attr( $cf7_gs['custom_value'][$i] ) : '' ;  ?>">
              
                    </select>

                  </td>


                  <td>
                    <label for="cf7-gs-custom-key<?php echo $i; ?>"><?php echo esc_html( __( 'GetResponse Custom Field Name', 'cf7-gr-ext' ) ); ?></label><br />
                    <input type="text" id="cf7-gs-custom-key<?php echo $i; ?>" name="cf7-gs[custom_key][<?php echo $i; ?>]" class="large-text" size="20" placeholder="example-field" value="<?php echo (isset( $cf7_gs['custom_key'][$i]) ) ?  esc_attr( $cf7_gs['custom_key'][$i] ) : '' ;  ?>" />
                  </td>

                  <td><br />
                    <?php
                    if( 2 <= $i ){
                      ?>
                      <a  data-cfid="<?php echo $i; ?>" class="dashicons dashicons-dismiss  delete remove-custom-field" style="
    color: red;
"  id="delete-custom-field" href="javascript:void(0);"></a>
                      <!-- <input data-cfid="<?php echo $i; ?>" type="button" name="delete-custom-field" id="delete-custom-field" class="button delete remove-custom-field" value="X"> -->
                      <?php
                    }
                    else{
                      echo '&nbsp;';
                    }
                    ?>
                  </td>

                </tr>
                  <?php
                } # END for($i=1;$i<=$count;$i++) ?>

                  <tr data-cfid='{{ID}}' class="custom-field-template" style="display:none">

                    <td class="">
                      <label for="{{CFV_FIELD_ID}}"><?php echo esc_html( __( 'Contact Form Value', 'cf7-gr-ext' ) ); ?></label><br />
                      <!-- <input type="text" id="{{CFV_FIELD_ID}}" name="{{CFV_FIELD_NAME}}" class="large-text" size="20" placeholder="[your-example-value]" value="" /> -->
                      <select class="field-names large-select"  id="{{CFV_FIELD_ID}}" name="{{CFV_FIELD_NAME}}" data-value="">
              
                    </select>
                    </td>


                    <td>
                      <label for="{{CFK_FIELD_ID}}"><?php echo esc_html( __( 'GetResponse Custom Field Name', 'cf7-gr-ext' ) ); ?></label><br />
                      <input type="text" id="{{CFK_FIELD_ID}}" name="{{CFK_FIELD_NAME}}" class="large-text" size="20" placeholder="example-field" value="" />
                    </td>

                    <td><br />
                    <a  data-cfid="{{ID}}" class="dashicons dashicons-dismiss delete remove-custom-field" style="
    color: red;
"  id="delete-custom-field" href="javascript:void(0);"></a>
                      <!-- <input data-cfid="{{ID}}" type="button" name="delete-custom-field" id="delete-custom-field" class="button delete remove-custom-field" value="X"> -->
                    </td>

                  </tr>
              </tbody>
            </table>
            <table class="form-table cf7-gs-custom-fields-tbl">
              <tbody>
                  <tr>
                    <td class=""></td>
                    <td></td>
                    <td><br />
                      <?php submit_button( __( '+ Custom Field', 'cf7-gr-ext' ), 'primary', 'cf7-gs-add-custom-field'); ?>
                    </td>
                  </tr>
              </tbody>
            </table>

        	</td>
      	</tr>

      </tbody>
    </table>

  </fieldset>
</div>
