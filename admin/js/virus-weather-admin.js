(function( jQuery ) {
	'use strict';

	jQuery(document).ready( function() {

		function virus_weather_close_popup() {
			jQuery('.mw-popup-bg, .mw-popup' ).fadeOut( function() {
				jQuery('.mw-popup-bg, .mw-popup' ).remove();
			})
		}

		jQuery('.virus-weather-mode input:checked').each( function() {
			if( jQuery(this).val() != 'static' ) {
				jQuery(this).closest('.widget').find('.virus-weather-country').addClass('hidden');
			}
		});

    
		setInterval( function() {
                    
                    
                        jQuery("input[name='static-widget-size'], input[name='static-widget-color']").click(function() {
				virus_weather_generate_widget_code();
                                jQuery('.dynamic_static_widget_get_code').removeClass('active');
			});	

			jQuery('[name*="widget-virus_weather_widget"][name*="size"]').unbind('change focus blur keyup click');
			jQuery('.virus-weather-theme input, .virus-weather-image img').unbind('click');
			jQuery('.virus-weather-theme input, .virus-weather-layout input, .virus-weather-size input' ).unbind('change click');

			jQuery('.virus-weather-layout input' ).on('change click', function() {
				virus_weather_widget_image( jQuery(this) );			
				virus_weather_update_size_input( jQuery(this).closest('.widget-content'), jQuery(this).val() );
			});

			jQuery('.virus-weather-theme input, .virus-weather-size input' ).on('change click', function() {
				virus_weather_widget_image( jQuery(this) );
			});	

			jQuery('[name*="widget-virus_weather_widget"][name*="size"]').unbind( 'change focus blur keyup click blur' );
			jQuery('[name*="widget-virus_weather_widget"][name*="size"]').on('change focus blur keyup click', function() {
				virus_weather_fix_widget_size( jQuery(this), 'focus' );
			}).on('blur', function() {
				virus_weather_fix_widget_size( jQuery(this), 'blur' );
			});

			jQuery('.virus-weather-mode input').unbind('click');
			jQuery('.virus-weather-mode input').on('click', function() {
				virus_weather_switch_type( jQuery(this) );
			});

			jQuery('.virus-weather-mode input:checked').each( function() {
				if( jQuery(this).val() != 'static' ) {
					jQuery(this).closest('.widget').find('.virus-weather-country').addClass('hidden');
				}
			});

			jQuery('.virus-weather-image img').css({'cursor':'zoom-in'}).attr('title', 'Click for full-size preview' ).click( function() {
				var elTrigger =  jQuery(this).closest('.widget-content');
				var elmwImage = elTrigger.find('.virus-weather-image img').clone();
				jQuery('body').append( jQuery('<div>').addClass('mw-popup').click( function () {
					 virus_weather_close_popup(); 
				} ).append( elmwImage ).append( jQuery('<span>').text('Click to close' )));
			});	

		}, 2000 );

		virus_weather_change_background();

		jQuery('#widget-virus_weather_widget-country').blur( function() {
			if( jQuery(this).val() == '' ) {
				jQuery('.virus-weather-static-selector.virus-weather-state, .virus-weather-static-selector.virus-weather-county').hide();				
			}
		});                
                jQuery('#widget-virus_weather_widget-state, #widget-virus_weather_widget-county').change( function() {
                        virus_weather_generate_widget_code();
                        jQuery('.dynamic_static_widget_get_code').removeClass('active');
                }); 
                jQuery('#vw-auto-location').change( function() {
                        virus_weather_generate_widget_code();
                        jQuery('.virus-weather-selected-location').text('');
                        jQuery('#widget-virus_weather_widget-country').val('');
                        jQuery('.dynamic_static_widget_get_code').removeClass('active');
                        jQuery('.virus-weather-state').hide();
                        jQuery('.virus-weather-county').hide();
                }); 
		jQuery('.virus-weather-static-selector.virus-weather-state, .virus-weather-static-selector.virus-weather-county').click( function() {
			jQuery('#widget-virus_weather_widget-countryautocomplete-list').hide();
		});		
		jQuery('#vw-fixed-location').click( function() {
			jQuery('#widget-virus_weather_widget-country').focus();                        
		});

                jQuery('#viewBlock').click( function(event) {
                    var imdgg = jQuery(this).children().clone();
					jQuery('body').append( jQuery('<div>').addClass('mw-popup').click( function () {
						 jQuery('.mw-popup-bg, .mw-popup' ).fadeOut( function() {
							jQuery('.mw-popup-bg, .mw-popup' ).remove();
						  });
						}).append( imdgg.addClass('zoomed-in') ).append( jQuery('<span>').text('Click to close' ))
			);
		})
		
	});	
	

})( jQuery );


		function virus_weather_widget_image( elInputs )
		{
			var elTrigger = false;
			elInputs.each( function() {
				if( jQuery(this).attr('id').match( /-\d+-/g )) {
					elTrigger = jQuery(this).closest('.widget');
				}
			});	

			if( jQuery('.virus-weather-selected-location').length > 0 )  {
				var mwWidgetCountry = jQuery('#widget-virus_weather_widget-country').val() || '';
				var mwWidgetState   = jQuery('#widget-virus_weather_widget-state').val() || '';
				var mwWidgetCounty  = jQuery('#widget-virus_weather_widget-county').val()  || '';
				jQuery('.virus-weather-selected-location').text(
					[ mwWidgetCountry, mwWidgetState, mwWidgetCounty ].join( ', ').replace( /[,\s]+$/g, '' )
				);
                                virus_weather_generate_widget_code();
                                jQuery('.dynamic_static_widget_get_code').removeClass('active');
			}

			if( ! elTrigger )
					return;
			
			/*virus_weather_fix_widget_size( elTrigger.find('[name*="size"]'), 'focus' );
			virus_weather_fix_widget_size( elTrigger.find('[name*="size"]'), 'blur' );
*/
			var mwWidgetTheme = elTrigger.find('input[id^="widget-virus_weather"][id*="-theme"]:checked').val();
			var mwWidgetLayout = elTrigger.find('input[id^="widget-virus_weather"][id*="-layout"]:checked').val();
			var mwWidgetType = elTrigger.find('input[id^="widget-virus_weather"][id*="-type"]:checked').val();

		 	var mwWidgetStatic = mwWidgetType == 'static';

			var mwWidgetSize    = elTrigger.find('input[id*="-size"]').val();
			var mwWidgetCountry = mwWidgetStatic && elTrigger.find('input[id*="-country"]').val() ? elTrigger.find('input[id*="-country"]').val() : '';
			var mwWidgetState   = mwWidgetStatic ? elTrigger.find('select[id*="-state"]').val() : '';
			var mwWidgetCounty  = mwWidgetStatic ? elTrigger.find('select[id*="-county"]').val() : '';
			
			var elWidgetShortcode  = elTrigger.find('.virus-weather-shortcode-value');

			var mwWidgetDimensions, mwWidgetWidth, mwWidgetHeight;
			var bCasesSource = false;
			var bCasesAdvanced = false;
			switch( mwWidgetLayout ) {
				case 'horizontal':
					mwWidgetDimensions = [ '1465', '180'];
					mwWidgetHeight = mwWidgetSize;
					mwWidgetW = /* mwWidgetDimensions[0] == '1465' ? 1456 : */ mwWidgetDimensions[0];
					mwWidgetWidth = Math.floor( mwWidgetHeight * mwWidgetW / mwWidgetDimensions[1] );
					break;
				case 'casesapp-advanced':
					mwWidgetDimensions = [ '1004', '1350'];
					mwWidgetWidth = mwWidgetSize;
					mwWidgetW = /* mwWidgetDimensions[0] == '1465' ? 1456 : */ mwWidgetDimensions[0];
					mwWidgetHeight = Math.floor( mwWidgetWidth * mwWidgetDimensions[1] / mwWidgetW  );
					bCasesSource = true;
					bCasesAdvanced = true;
					break;
				case 'casesapp':
					mwWidgetDimensions = [ '1004', '1350'];
					mwWidgetWidth = mwWidgetSize;
					mwWidgetW = /* mwWidgetDimensions[0] == '1465' ? 1456 : */ mwWidgetDimensions[0];
					mwWidgetHeight = Math.floor( mwWidgetWidth * mwWidgetDimensions[1] / mwWidgetW  );
					bCasesSource = true;
					break;
				default:
					mwWidgetDimensions = [ '1000' ];
					mwWidgetWidth = mwWidgetHeight = mwWidgetSize;
					break;
			}

			if( mwWidgetCountry == 'United States' ) {
				elTrigger.find('.virus-weather-state label').text('State');
				elTrigger.find('.virus-weather-county label').text('County');
			} else {
				elTrigger.find('.virus-weather-state label').text('Area');
				elTrigger.find('.virus-weather-county label').text('District');
			}

			

			virus_weather_update_location_labels( mwWidgetCountry, elTrigger );

			var widgetSrc = ( bCasesSource ? 'https://infectionrank.org/' : 'https://www.markosweb.com/' ) 
				+ ( mwWidgetCountry ? 'coronavirus' : 'widgets' ) + '/en'
				+ ( mwWidgetCountry ? '/' + virus_weather_get_location_id( mwWidgetCountry ) : '' )
				+ ( mwWidgetState ? '/' + virus_weather_get_location_id( mwWidgetState ) : '' )
				+ ( mwWidgetCounty ? '/' + virus_weather_get_location_id( mwWidgetCounty ) : '' )
				+ ( bCasesSource ? '/vw-app-' + ( bCasesAdvanced ? 'advanced-' : '' ): '/vw-covid-19-' ) + mwWidgetTheme + '-'
				+ ( mwWidgetDimensions.length > 1 ? mwWidgetDimensions.join('x') : mwWidgetDimensions[0] )  +'.png';
			elTrigger.find('.virus-weather-image img')
				.attr('src', widgetSrc ).attr('width', mwWidgetWidth ).attr('height', mwWidgetHeight );
                        
                        jQuery('#viewBlock').html( '<img alt="covid-19 widget"  src="' + widgetSrc + '" />' );

			// Update shortcode
			stateParam = 'area';
			countyParam = 'district';
			if( mwWidgetCountry == 'United States' ) {
				stateParam = 'state';
				countyParam = 'county';
			}
		 
			elWidgetShortcode.val( '[virusweather' +
				( mwWidgetTheme == 'light' ? '' : ' theme="' + mwWidgetTheme + '"' ) +
				( mwWidgetLayout == 'square' ? '' : ' layout="' + mwWidgetLayout + '"' ) +
				( mwWidgetCountry ? ' country="' + mwWidgetCountry + '"' : '' ) +
				( mwWidgetCountry && mwWidgetState ? ' '+ stateParam +'="' + mwWidgetState + '"' : '' ) +
				( mwWidgetCountry && mwWidgetState && mwWidgetCounty ? ' '+ countyParam +'="' + mwWidgetCounty + '"' : '' ) +
				( ( mwWidgetLayout == 'square' && mwWidgetSize == 250 ) || ( mwWidgetLayout == 'casesapp' && mwWidgetSize == 300 ) || ( mwWidgetLayout == 'horizontal' && mwWidgetSize == 90 ) ? '' : ' size="' + mwWidgetSize + '"' ) + ']' );

		}

		function virus_weather_update_location_labels( country, elTrigger )
		{
			if( country == 'United States' ) {
				elTrigger.find('.virus-weather-state label').text('State');
				elTrigger.find('.virus-weather-county label').text('County');
			} else {
				elTrigger.find('.virus-weather-state label').text('Area');
				elTrigger.find('.virus-weather-county label').text('District');
			}
		}

		function virus_weather_get_location_id( location )
		{
			return location.toLowerCase().replace( /\s/g, '-' ).replace( /[^a-z-]/g, '' );
		}

		function virus_weather_switch_type( widgettype )
		{
			var elTrigger =  widgettype.closest('.widget-content');
			if( elTrigger.find( 'input[id*="-type"]:checked').val() == 'static' ) {
				elTrigger.find('.virus-weather-country').removeClass('hidden');
			} else {
				elTrigger.find('.virus-weather-country, .virus-weather-state, .virus-weather-county').addClass('hidden').removeAttr('style');
				elTrigger.find('.virus-weather-country').val('');
				elTrigger.find('.virus-weather-state select, .virus-weather-county select').html('');
			}
			virus_weather_widget_image( widgettype );
		}

		function virus_weather_fix_widget_size( elSize, evType )
		{
			if( evType == 'focus' ) {
				if( parseInt( elSize.val() ) > parseInt( elSize.attr('max'))) {
					elSize.val( elSize.attr('max') );
				}	
			}
			if( evType == 'blur' ) {
				if( parseInt( elSize.val() ) < parseInt( elSize.attr('min'))) {
					elSize.val( elSize.attr('min') );
				}
			}

			virus_weather_widget_image( elSize );
		}

	    function virus_weather_update_size_input( wrapper, layout )
		{
			var mwSizeLbl = wrapper.find('.virus-weather-size > label');
			var mwSIzeInput = wrapper.find('input[id*="-size"]');
			mwSizeLbl.html( mwSizeLbl.data('text-' + layout ) + '&nbsp;' );

			var mwLayoutMin = mwSIzeInput.data('min-' + layout );
			var mwLayoutMax = mwSIzeInput.data('max-' + layout );
			mwSIzeInput.attr('min', mwLayoutMin );
			mwSIzeInput.attr('max', mwLayoutMax );

			if( layout == 'horizontal' ) {
				mwSIzeInput.val( mwLayoutMax );
			} else {
				if( mwSIzeInput.val() > mwLayoutMax )
					mwSIzeInput.val( mwLayoutMax );
				if( mwSIzeInput.val() < mwLayoutMin )
					mwSIzeInput.val( mwLayoutMin );
			}
			//mwSIzeInput.val( mwLayoutMin );
			wrapper.find('.virus-weather-size').css({'display': layout == 'horizontal' ? 'none' : 'block' })

			wrapper.find('.virusweather-size-range').html( '(' + mwLayoutMin + '&ndash;' + mwLayoutMax + ' pixels)' );
		}


		/* Settings page code generator */

		function virus_weather_generate_widget_code() {

		    var strWidgetType = jQuery("input[name='static-widget-type']:checked").val();
		    var strWidgetColor = jQuery("input[name='static-widget-color']:checked").val();
		    var strWidgetSize = jQuery("input[name='static-widget-size']:checked").val();
		    var strWidgetCode = jQuery("input[name='static-widget-html-wp-code']:checked").val();

			var GCLID = null;

		    if( GCLID ) { widgetUrl += '?vwid=' + GCLID; }; 
		    var imageSizeAtrs = virus_weather_get_height_width_image(strWidgetSize);
		    if( strWidgetType == 'dynamic' ) {
		        var currentPath = 'en';
		        var widgetUrl = 'https://infectionrank.org/widgets/'+ currentPath + '/vw-covid-19-' + strWidgetColor + '-' +strWidgetSize+ '.png';
		        if(strWidgetSize == 'basic1004x1350') {
		          widgetUrl = 'https://infectionrank.org/widgets/'+ currentPath + '/vw-app-' + strWidgetColor + '-1004x1350.png';
		        }
		        if(strWidgetSize == 'advanced1004x1350') {
		          widgetUrl = 'https://infectionrank.org/widgets/'+ currentPath + '/vw-app-advanced-' + strWidgetColor + '-1004x1350.png';
		        }

		        if(strWidgetCode === 'htmlType') {
		            var html_dynamic = '<a href="https://infectionrank.org/" title="Infection Rank"><img alt="Public health ratings app and widgets" width="' + 
		            	imageSizeAtrs.width + '" height="' + imageSizeAtrs.height + '" src="' + widgetUrl + '" /></a>';
		        }
		        if(strWidgetCode === 'wordpressType') {
		            var html_dynamic = virus_weather_generate_wp_shortcode(strWidgetType, strWidgetColor, strWidgetSize, strWidgetCode, currentPath);
		        }

		        jQuery('#dynamic_static_cource_code').text( html_dynamic );
		        jQuery('.dynamic_static_widget_get_code').addClass('active');

		        jQuery('#previewImg').show().html('').append( 
		        	jQuery('<img>')
		        		.attr('alt', 'COVID-19 Widget')
		        		.attr('src', widgetUrl )
		        		.attr('width', imageSizeAtrs.width )
		        		.attr('height', imageSizeAtrs.height )
		        		.attr('title', 'Click for full-size preview' )
		        		.css({'cursor':'zoom-in'})
		        		.addClass(imageSizeAtrs.width == '728' ? 'imgHoriz' : 'imgVertical')
		        		.click( function() {
					var elmwImage = jQuery(this).clone();
					jQuery('body').append( jQuery('<div>').addClass('mw-popup').click( function () {
						 jQuery('.mw-popup-bg, .mw-popup' ).fadeOut( function() {
							jQuery('.mw-popup-bg, .mw-popup' ).remove();
						  });
						}).append( elmwImage.addClass('zoomed-in') ).append( jQuery('<span>').text('Click to close' ))
					);
					})
		        );
                        
		        
		        jQuery('#viewBlock').html( '<img alt="covid-19 widget" src="' + widgetUrl + '" />' );
		    }

		    if( strWidgetType == 'static' ) {
		        var currentPath = 'en';

		    	var getCountry = jQuery('#widget-virus_weather_widget-country').val();
  				var getState = jQuery('#widget-virus_weather_widget-state').val();
		    	var getCounty = jQuery('#widget-virus_weather_widget-county').val();

				currentPath += getCountry ? '/' + virus_weather_get_location_id( getCountry ) : '';
				currentPath += getState ? '/' + virus_weather_get_location_id( getState ) : '';
				currentPath += getCounty ? '/' + virus_weather_get_location_id( getCounty ) : '';
				currentPath += '/';

		        var widgetUrl = 'https://infectionrank.org/coronavirus/'+ currentPath + 'vw-covid-19-' + strWidgetColor + '-' +strWidgetSize+ '.png';
		        if(strWidgetSize == 'basic1004x1350') {
		          widgetUrl = 'https://infectionrank.org/coronavirus/'+ currentPath + 'vw-app-' + strWidgetColor + '-1004x1350.png';
		        }
		        if(strWidgetSize == 'advanced1004x1350') {
		          widgetUrl = 'https://infectionrank.org/coronavirus/'+ currentPath + 'vw-app-advanced-' + strWidgetColor + '-1004x1350.png';
		        }
		        if(strWidgetCode === 'htmlType') {
		            var html_static = '<a href="https://infectionrank.org/coronavirus/'+ currentPath.split("en/")[1] + 'latest-stats/" title="Infection Rank"><img alt="Public health ratings app and widgets" width="' + 
		            	imageSizeAtrs.width + '" height="' + imageSizeAtrs.height + '"  src="' + widgetUrl + '" /></a>';
		        }

		        if(strWidgetCode === 'wordpressType') {
		            var html_static = virus_weather_generate_wp_shortcode(strWidgetType, strWidgetColor, strWidgetSize, strWidgetCode, currentPath);
		        }

		        
		        jQuery('#dynamic_static_cource_code').text( html_static );
		        jQuery('.dynamic_static_widget_get_code').addClass('active');

		        jQuery('#previewImg').show().html('').append( 
		        	jQuery('<img>')
		        		.attr('alt', 'COVID-19 Widget')
		        		.attr('src', widgetUrl )
		        		.attr('width', imageSizeAtrs.width )
		        		.attr('height', imageSizeAtrs.height )
		        		.attr('title', 'Click for full-size preview' )
		        		.css({'cursor':'zoom-in'})
		        		.addClass(imageSizeAtrs.width == '728' ? 'imgHoriz' : 'imgVertical')
		        		.click( function() {
					var elmwImage = jQuery(this).clone();
					jQuery('body').append( jQuery('<div>').addClass('mw-popup').click( function () {
						 jQuery('.mw-popup-bg, .mw-popup' ).fadeOut( function() {
							jQuery('.mw-popup-bg, .mw-popup' ).remove();
						  });
						}).append( elmwImage.addClass('zoomed-in') ).append( jQuery('<span>').text('Click to close' ))
					);
					})
		        );

		        jQuery('#viewBlock').html( '<img alt="covid-19 widget" src="' + widgetUrl + '" />' );
		    }

		    virus_weather_generate_wp_shortcode(strWidgetType, strWidgetColor, strWidgetSize, strWidgetCode, currentPath);
		}


		function virus_weather_generate_wp_shortcode( type, theme, size, code, currentPath ) {
		    //const objState = locations.filter(l => l.path === ('en/' + currentPath + '/'))[0];
		    var getState = jQuery('#widget-virus_weather_widget-state').val();
		    var getCounty = jQuery('#widget-virus_weather_widget-county').val();
		    var getCountry = jQuery('#widget-virus_weather_widget-country').val();
		    var layout = virus_weather_banner_name_by_size(size);
		    var wpTheme = theme == 'light' ? '' : ' theme="' + theme + '"';
		    var wplayout = layout == 'square' ? '' : ' layout="' + layout + '"';

		    if(getCountry){
		        var wpCountry = '';
		        var wpState = '';
		        var wpCounty = '';
		        var locale = ''; 

		        if(getCountry){
		            wpCountry = ' country="' + getCountry + '"';
		            locale = getCountry;
		        }   

		        if(getCountry && getState){
		            if(getCountry == 'United States'){
		                wpState = ' state="' + getState + '"';
		            }else{
		                wpState = ' area="' + getState + '"';
		            }
		            locale =  locale + ', ' + getState;
		        }

		        if(getCountry && getState && getCounty){
		            if(getCountry == 'United States'){
		                wpCounty = ' county="' + getCounty + '"';
		            }else{
		                wpCounty = ' district="' + getCounty + '"';
		            }
		            locale =  locale + ', ' + getCounty;
		        }

		    } else {
		        var wpCountry = '';
		        var wpState = '';
		        var wpCounty = '';
		    }

		    return '[virusweather' + wpTheme +''+ wplayout +''+ wpCountry +''+ wpState +''+ wpCounty +']';
		}

		function virus_weather_change_background() {    
		    jQuery('input[name="static-widget-color"]').change(function (data) {
		        data.target.value == 'light' ? typePrefix = '_g' : typePrefix = '';

		        var basicUrl = jQuery('#vw-image-w250').data('basic-src');
		        jQuery('#vw-image-w250').attr("src", basicUrl + 'ico_size_250x250' + typePrefix +".png");
		        jQuery('#vw-image-w300').attr("src", basicUrl + 'ico_size_300x400' + typePrefix +".png");
		        jQuery('#vw-image-w300a').attr("src", basicUrl + 'ico_size_300x400' + typePrefix +".png");
		        jQuery('#vw-image-w728').attr("src", basicUrl + 'ico_size_728x90' + typePrefix +".png");
		    });
		}


		function virus_weather_get_height_width_image(widgetSize){
		    var imageSize = {};
		    switch (widgetSize) {
		      case '1000':
		        imageSize = { 'width' : "250", 'height' : "250" };
		        break;
		      case 'basic1004x1350':
		      case 'advanced1004x1350':
		        imageSize = { 'width' : "300", 'height' : "400" };
		        break;
		      case '1465x180':
		        imageSize = { 'width' : "728", 'height' : "90" };
		        break;        
		      default:
		        imageSize = { 'width' : "250", 'height' : "250" };
		    }
		    return imageSize;
		}

		function virus_weather_banner_name_by_size(size){
		    var bannerName = '';
		    switch (size) {
		      case '1000':
		        bannerName = 'square';
		        break;
		      case 'basic1004x1350':
		        bannerName = 'casesapp';
		        break;
		      case 'advanced1004x1350':
		        bannerName = 'casesapp-advanced';
		        break;
		      case '1465x180':
		        bannerName = 'horizontal';
		        break;        
		      default:
		        bannerName = 'square';
		        break;
		    }
		    return bannerName;
		}


		function virus_weather_copy_widget_source( strIdSelect ) {
		    var copyText = document.getElementById(strIdSelect);
		    copyText.select();
		    copyText.setSelectionRange(0, 99999);
		    document.execCommand("copy");
//
//		    let tooltip = document.getElementById("virus-weather-tooltip");
//		    tooltip.style.display = 'block';
//		    tooltip.innerHTML = "Copied";
		}





