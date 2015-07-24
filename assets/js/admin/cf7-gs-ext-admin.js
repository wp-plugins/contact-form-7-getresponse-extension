jQuery( function( $ ){

      // Add now custom field on button click
      $( '#cf7-gs-add-custom-field' ).click( function(){

          // check the limit
          if( $("tr.custom-field-single").length > 19 ){
            alert('Max limit of custom fields is 20' );
            return false;
          }

          // Count number of current custom fields
          var countCustomFields = $("tr.custom-field-single:last").data( "cfid" );

          // Id for next custom field
          var nextCustomFieldId = parseInt( countCustomFields )+1;

          // Clone template
          var cloneTemplate = $( "tr.custom-field-template" ).clone();

          var newElement = cloneTemplate[0]['outerHTML'].replace( /{{ID}}/g, nextCustomFieldId );
          newElement = newElement.replace( /{{CFV_FIELD_ID}}/g, "cf7-gs-custom-value"+nextCustomFieldId );
          newElement = newElement.replace( /{{CFV_FIELD_NAME}}/g, "cf7-gs[custom_value]["+nextCustomFieldId+"]" );
          newElement = newElement.replace( /{{CFK_FIELD_ID}}/g, "cf7-gs-custom-key"+nextCustomFieldId );
          newElement = newElement.replace( /{{CFK_FIELD_NAME}}/g, "cf7-gs[custom_key]["+nextCustomFieldId+"]" );

          console.log( newElement );

          // Change cloned element values
          $( newElement ).insertAfter("tr.custom-field-single:last"  ).addClass( 'newClonedCustomFields' );

          $( ".newClonedCustomFields" ).removeClass( 'custom-field-template' )
                                       .addClass( 'custom-field-single' ).fadeIn('slow');

          $( "tr.custom-field-single:last" ).removeClass( 'newClonedCustomFields' );

          return false;

      });

      // remvoe Custom Fields
      $( 'body' ).on( 'click', 'input.remove-custom-field', function(){

        if( confirm( 'Are you sure to delte?' ) ){

          var cfid = $(this).data('cfid');
          $( "tr[data-cfid="+cfid+"]").fadeOut( 'slow', function(){
            $(this).remove();
          });

          return false;

        }
        return false;
      });


      $( 'body' ).on( 'click', 'a#cf7-gs-ext-update-camp', function(){
        $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="spinner" style="visibility:visible;float: none;"></span> Loading...' );
        $.post( cf7_options.ajax_url,{action:'gs_update_camp'}, function(data){
            if( '' != data && 0 != data ){
              $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="dashicons dashicons-yes"></span> Updated');
            }
            else{
              $( 'a#cf7-gs-ext-update-camp' ).html( '<span class="dashicons dashicons-no-alt"></span> Error Updating');
            }
        });
        return false;
      } );

      $( 'body' ).on( 'click', 'a#cf7-gs-ext-update-select-camp', function(){
        var $this = $(this);
        $this.html( '<span class="spinner" style="visibility:visible;float: none;"></span>' );
        $.post( cf7_options.ajax_url,{action:'gs_update_camp'}, function(data){
            if( '' != data && 0 != data ){
              $this.html( '<span class="dashicons dashicons-yes"></span>');
              var campCount = _.size(data.gs_camp);
              var oHTML = '<option value="">Select Campaign</option>';

              $.each( data.gs_camp, function( key, value ) {
                  oHTML+= '<option value="'+key+'">'+value.name+'</option>';
              });

              $this.prev().prev().html( oHTML );
            }
            else{
              $this.html( '<span class="dashicons dashicons-no-alt"></span>');
            }
        }, 'json');
        return false;
      } );
});
