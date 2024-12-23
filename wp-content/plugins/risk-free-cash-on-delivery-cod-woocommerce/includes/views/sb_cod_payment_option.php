<?php
// to check wether accessed directly
if (!defined('ABSPATH')) {
	exit;
}
?>	
<strong><?php _e( 'Configuration Rules Table', '' ); ?></strong><br><br>
		<style type="text/css">
			.sb_boxes td, .sb_services td {
				vertical-align: middle;
				padding: 4px 7px;
			}
			.sb_services th, .sb_boxes th {
				padding: 9px 7px;
			}
			.sb_boxes td input {
				margin-right: 4px;
			}
			.sb_boxes .check-column {
				vertical-align: middle;
				text-align: left;
				padding: 0 7px;
			}
			.sb_services th.sort {
				width: 16px;
				padding: 0 16px;
			}
			.sb_services td.sort {
				cursor: move;
				width: 16px;
				padding: 0 16px;
				cursor: move;
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAHUlEQVQYV2O8f//+fwY8gJGgAny6QXKETRgEVgAAXxAVsa5Xr3QAAAAASUVORK5CYII=) no-repeat center;
			}
		</style>
                <?php
				 $sb_fetch_roles = wp_roles();
				 $pack_type = array('all' => 'ALL');
				 $sb_fetch_countrylist = array('all' => 'ALL');
				 $sb_fetch_advance_type = array('per' => 'Percentage','money' => 'Amount');
				 $countries_obj   = new WC_Countries();
				 $countries   = $countries_obj->__get('countries');
				 if(isset($sb_fetch_roles->roles))
				 {
					 foreach($sb_fetch_roles->roles as $key => $role)
					 {
						 $pack_type[$key] = $role['name'];
						
					 }
				 }
				 if(!empty($countries))
				 {
					 foreach($countries as $key => $value)
					 {
						 $sb_fetch_countrylist[$key] = $value;
						
					 }
				 }
                $option_string1 = '';
               foreach ($pack_type as $k => $v) {
                    $selected = ($k == 'YP')? 'selected="selected"' : '';
                    $option_string1.='<option value="'.$k.'"'.$selected.' >'.$v.'</option>';
                }
				$option_string2 = '';
               foreach ($sb_fetch_countrylist as $k => $v) {
                    $selected = ($k == 'YP')? 'selected="selected"' : '';
                    $option_string2.='<option value="'.$k.'"'.$selected.' >'.$v.'</option>';
                }
				$option_string3 = '';
               foreach ($sb_fetch_advance_type as $k => $v) {
                    $selected = ($k == 'YP')? 'selected="selected"' : '';
                    $option_string3.='<option value="'.$k.'"'.$selected.' >'.$v.'</option>';
                }
                ?>
		<table class="sb_boxes widefat">
			<thead>
				<tr style="text-align:center;">
					<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'Name', '' ); ?><br/><small style="color:green;"> ( Free )</small></th>
					<th><?php _e( 'Advance', '' ); ?><br/><small style="color:green;"> ( Free )</small></th>
					<th><?php _e( 'Advance Type', '' ); ?><br/><small style="color:green;"> ( Free )</small></th>
					<th><?php _e( 'Extra Charges', '' ); ?><br/><small style="color:green;"> ( Free )</small></th>
					<th><?php _e( 'Min Total', '' ); ?><br/><small style="color:red;"> ( Premium )</small></th>
					<th><?php _e( 'Max Total', '' ); ?><br/><small style="color:red;"> ( Premium )</small></th>
					<th><?php _e( 'Pin Code', '' ); ?><br/><small style="color:red;"> ( Premium )</small></th>
					<th><?php _e( 'Country', '' ); ?><br/><small style="color:red;"> ( Premium )</small></th>
					<th><?php _e( 'User Role', '' ); ?><br/><small style="color:red;"> ( Premium )</small></th>
					<th><?php _e( 'Enabled', '' ); ?><br/><small style="color:green;"> ( Free )</small></th>
				</tr>
			</thead>
			<tfoot>
			<tr style="text-align:center;">	<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'Name', '' ); ?></th>
					<th><?php _e( 'Advance', '' ); ?></th>
					<th><?php _e( 'Advance Type', '' ); ?></th>
					<th><?php _e( 'Extra Charges', '' ); ?></th>
					<th><?php _e( 'Min Total', '' ); ?></th>
					<th><?php _e( 'Max Total', '' ); ?></th>
					<th><?php _e( 'Pin Code', '' ); ?></th>
					<th><?php _e( 'Country', '' ); ?></th>
					<th><?php _e( 'User Role', '' ); ?></th>
					<th><?php _e( 'Enabled', '' ); ?></th>
				</tr>
				<tr>
					<th colspan="3">
						<a href="#" class="plus insert button button-secondary" title="Add New Rule" style="vertical-align: middle;"><span class="dashicons dashicons-edit" style="vertical-align: middle;
    font-size: 17px;"></span></a>
						<a href="#" class=" minus remove button button-secondary" title="Remove Selected Rule(s)"><span class="dashicons dashicons-trash" style="vertical-align: middle;
    font-size: 17px;"></span></a>
					</th>
					<th colspan="10" >
						<small class="description" style="visibility:hidden"><?php _e( 'Click <span class="dashicons dashicons-edit" style="vertical-align: middle;
    font-size: 14px;"></span> Button to Add New Rule and <span class="dashicons dashicons-trash" style="vertical-align: middle;
    font-size: 14px;"></span> Button to remove the Selected Rule.', '' ); ?></small>
					</th>
				</tr>
				
			</tfoot>
			<tbody id="rates">
				<?php
					if ( $this->rules ) {
						foreach ( $this->rules as $key => $rule ) {
							?>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<input type="hidden" size="1" name="rules_id[<?php echo $key; ?>]" value="<?php echo esc_attr( $rule['id'] ); ?>" />
								<td><input type="text" size="25" name="rules_name[<?php echo $key; ?>]" value="<?php echo esc_attr( $rule['name'] ); ?>" /></td>

								<td><input type="number" step="any" style="width:100%;" name="rules_advance1[<?php echo $key; ?>]" value="<?php echo esc_attr( $rule['advance1'] ); ?>" /></td>
								<td><select  name="rules_advance2[<?php echo $key; ?>]" >
								<?php foreach ($sb_fetch_advance_type as $k => $v) { ?>
									   	<option value="<?php echo $k; ?>" <?php if( $k == (isset($rule['advance2']) ? $rule['advance2'] : '' ) ): ?> selected="selected"<?php endif; ?>><?php echo $v; ?> </option>
								    <?php }?>
									</select></td>
								<td><input type="number" step="any" style="width:100%;" name="rules_fee[<?php echo $key; ?>]" value="<?php echo esc_attr( $rule['fee'] ); ?>" /></td>
							
								<td><input type="number" step="any" disabled="true" style="width:100%;"  /></td>
								<td><input type="number" step="any" style="width:100%;" disabled="true"  /></td>
								
								<td><input type="text" style="width:100%;" disabled="true"/></td>
								<td><select style="width:70%;" disabled="true" >
								<?php foreach ($sb_fetch_countrylist as $k => $v) { ?>
									   	<option value="<?php echo $k; ?>"><?php echo $v; ?> </option>
								    <?php }?>
									</select></td>
								<td><select style="width:95%;" disabled="true" >
									<?php foreach ($pack_type as $k => $v) { ?>
									   	<option value="<?php echo $k; ?>" ><?php echo $v; ?> </option>
								    <?php }?>
									</select>
								</td>
								<td><input type="checkbox" name="rules_enabled[<?php echo $key; ?>]" <?php checked( $rule['enabled'], true ); ?> /></td>
								
							</tr>
							<?php
						}
					}
					else
					{
						echo "<tr id='sb_no_box_state'><td colspan='13' style='padding: 15px;background: #f1f1f1;color:black;'></td></tr>";
					}
				?>
			</tbody>
		</table>
		<script type="text/javascript">

			jQuery(window).load(function(){

                                var pack_type_options = '<?php echo $option_string1; ?>';
                                var country_lists = '<?php echo $option_string2; ?>';
                                var adavnce_type = '<?php echo $option_string3; ?>';
				jQuery('.sb_boxes .insert').click( function() {
					var $tbody = jQuery('.sb_boxes').find('tbody');
					var size = $tbody.find('tr').size();
					var size = size+1;
					jQuery('#sb_no_box_state').hide();
					var code = '<tr class="new">\
							<td class="check-column" style="text-align: center;"><input type="checkbox" /></td>\
							<input type="hidden" size="1" name="rules_id[' + size + ']" />\
							<td><input type="text" size="25" name="rules_name[' + size + ']" /></td>\
							<td><input type="number" step="any" style="width:100%;" name="rules_advance1[' + size + ']" /></td>\
							<td><select style="width:95%;" name="rules_advance2[' + size + ']" >' + adavnce_type + '</select></td>\
							<td><input type="number" step="any" style="width:100%;" name="rules_fee[' + size + ']" /></td>\
							<td><input type="number" step="any" style="width:100%;" disabled="true" /></td>\
							<td><input type="number" step="any" style="width:100%;" disabled="true"/></td>\
							<td><input type="text" style="width:100%;" disabled="true" /></td>\
							<td><select style="width:70%;" disabled="true" >' + country_lists + '</select></td>\
							<td><select style="width:95%;" disabled="true" >' + pack_type_options + '</select></td>\
							<td><input type="checkbox" name="rules_enabled[' + size + ']" /></td>\
                                                     </tr>';

					$tbody.append( code );

					return false;
				} );

				jQuery('.sb_boxes .remove').click(function() {
					var $tbody = jQuery('.sb_boxes').find('tbody');

					$tbody.find('.check-column input:checked').each(function() {
						jQuery(this).closest('tr').hide().find('input').val('');
					});

					return false;
				});

				// Ordering
				jQuery('.sb_services tbody').sortable({
					items:'tr',
					cursor:'move',
					axis:'y',
					handle: '.sort',
					scrollSensitivity:40,
					forcePlaceholderSize: true,
					helper: 'clone',
					opacity: 0.65,
					placeholder: 'wc-metabox-sortable-placeholder',
					start:function(event,ui){
						ui.item.css('background-color','#f6f6f6');
					},
					stop:function(event,ui){
						ui.item.removeAttr('style');
						sb_services_row_indexes();
					}
				});

				function sb_services_row_indexes() {
					jQuery('.sb_services tbody tr').each(function(index, el){
						jQuery('input.order', el).val( parseInt( jQuery(el).index('.sb_services tr') ) );
					});
				};

			});

		</script>