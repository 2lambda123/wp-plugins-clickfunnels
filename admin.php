<?php    $cf_thefunnel = get_post_meta( $_GET['post'], "cf_thefunnel", true );    $cf_thepage = get_post_meta( $_GET['post'], "cf_thepage", true );    if ( !isset( $cf_page['page_id'] ) || $cf_page['page_id'] < 10 ) {        $thepage = explode( "{#}", $cf_page );        $savedData = $cf_page;    }    else {        $thepage = explode( "{#}", $cf_page['page_id'] );        $savedData = $cf_page['page_id'];    }    $cf_options = get_option( "cf_options" );?><script type="text/javascript">    jQuery(document).ready(function(){        // Set Correct Options        var thefunnel = jQuery('#cf_thefunnel').val();        var specificFunnel = 'https://api.clickfunnels.com/funnels/'+thefunnel+'.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';        jQuery('#cf_thepage').find('option').remove().end();        jQuery('#loading').fadeIn();        jQuery.getJSON(specificFunnel, function(data) {            setTimeout(function() {               jQuery('#loading').fadeOut();            }, 2000);            var is_selected = "";            jQuery.each(data.funnel_steps, function() {            if (this.pages[0].id == "<?php echo $thepage[2] ?>") {                is_selected = "selected";                jQuery('#cf_seo_tags').val(this.pages[0].metatags);            } else {                is_selected = "";            }                                if ( this.wp_friendly == true && this.in_funnel == true ) {                    jQuery('#cf_thepage').append('<option value="' + this.pages[0].key + '{#}' + this.pages[0].id + '{#}' + this.pages[0].funnel_step_id + '{#}' + encodeURI(this.pages[0].metatags) + '{#}<?php echo get_the_ID(); ?>" '+is_selected+'>'+ this.name +'</option>');                }                                            });          }).done(function() {            jQuery('#loading').fadeOut();            jQuery('#cf_thepage').trigger( "change" );          })          .fail(function() {            jQuery('#loading').fadeOut();          })          .always(function() {            jQuery('#loading').fadeOut();            console.log( "ClickFunnels Rocks!" );          });        // Change Funnel Populate Dropdown        jQuery( '#cf_thefunnel' ).change(function() {            jQuery('#loading').fadeIn();            var thefunnel = jQuery(this).val();            var totalPages = 0;            var specificFunnel = 'https://api.clickfunnels.com/funnels/'+thefunnel+'.json?email=<?php echo get_option( "clickfunnels_api_email" ); ?>&auth_token=<?php echo get_option( "clickfunnels_api_auth" ); ?>';            jQuery('#cf_thepage').find('option').remove().end();                jQuery.getJSON(specificFunnel, function(data) {                    setTimeout(function() {                       jQuery('#loading').fadeOut();                    }, 2000);                    // alert( JSON.stringify(data.funnel_steps));                jQuery.each(data.funnel_steps, function() {                    if( this.wp_friendly == true && this.in_funnel == true ) {                        jQuery('#cf_thepage').append('<option value="' + this.pages[0].key + '{#}' + this.pages[0].id + '{#}' + this.pages[0].funnel_step_id + '{#}' + encodeURI(this.pages[0].metatags) + '{#}<?php echo get_the_ID(); ?>">'+ this.name +'</option>');                        totalPages += 1;                    }                });            }).done(function() {                jQuery('#loading').fadeOut();              })              .fail(function() {                jQuery('#loading').fadeOut();              })              .always(function() {                jQuery('#loading').fadeOut();              });            setTimeout(function() {                if (totalPages == 0) {                    jQuery('#noPageWarning').fadeIn();                }                else {                    jQuery('#noPageWarning').hide();                }                var theposition = jQuery('#cf_thepage').val();                jQuery('#cf_data').val(thefunnel+'{#}'+theposition);                var myString = thefunnel+'{#}'+theposition;                var arr = myString.split('{#}');                // jQuery('#loadPageforUpdate').attr('src', 'https://api.clickfunnels.com/s3_proxy/'+arr[1]+'?preview=true');                var do_ping = function() {                    ping('https://api.clickfunnels.com/s3_proxy/'+arr[1]+'?preview=true').then(function(delta) {                                            }).catch(function(error) {                                            });                };                do_ping();            }, 3000);        });        var request_image = function(url) {            return new Promise(function(resolve, reject) {                var img = new Image();                img.onload = function() { resolve(img); };                img.onerror = function() { reject(url); };                img.src = url + '?random-no-cache=' + Math.floor((1 + Math.random()) * 0x10000).toString(16);            });        };        /**         * Pings a url.         * @param  {String} url         * @return {Promise} promise that resolves to a ping (ms, float).         */        var ping = function(url) {            return new Promise(function(resolve, reject) {                var start = (new Date()).getTime();                var response = function() {                     var delta = ((new Date()).getTime() - start);                                        // HACK: Use a fudge factor to correct the ping for HTTP bulk.                    delta /= 4;                                        resolve(delta);                 };                request_image(url).then(response).catch(response);                                // Set a timeout for max-pings, 5s.                setTimeout(function() { reject(Error('Timeout')); }, 5000);            });        };        // Select New Page        jQuery( '#cf_thepage' ).change(function() {            jQuery('#loading').fadeOut();            var thefunnel = jQuery('#cf_thefunnel').val();            var theposition = jQuery(this).val();            jQuery('#cf_data').val(thefunnel+'{#}'+theposition);            var myString = thefunnel+'{#}'+theposition;                var arr = myString.split('{#}');                // jQuery('#loadPageforUpdate').attr('src', 'https://api.clickfunnels.com/s3_proxy/'+arr[1]+'?preview=true');                var do_ping = function() {                    ping('https://api.clickfunnels.com/s3_proxy/'+arr[1]+'?preview=true').then(function(delta) {                                            }).catch(function(error) {                                            });                };                do_ping();        });        // Fade Out Message        setTimeout(function() {            jQuery('#message').fadeOut();        }, 3500);        // Savings        jQuery('#publish').click(function() {            jQuery('#saving').fadeIn();        });        jQuery('#delete').on('click', function () {            return confirm('Are you sure you want to delete this page?');        });        jQuery('#cf_slug').bind('keyup keypress blur', function()        {            var myStr = jQuery(this).val()            myStr=myStr.toLowerCase();            myStr=myStr.replace(/\s/g , "-");            jQuery('#cf_slug').val(myStr);        });        // Check Null and Resave v1.0.6        <?php if ($thepage[1] == 'null') { ?>            jQuery('#autosaving').fadeIn();            setTimeout(function() {                jQuery('form').submit();            }, 2000);        <?php } ?>    });</script><link href="<?php echo plugins_url( 'css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet"><style>.wrap h2 {        display: none !important;    }    #side-sortables {        display: none !important;    }    #clickfunnels_meta_box {        width: 780px !important;        border-radius: 5px;    }     .button:active,  .button:hover,  .button:focus {        outline: none !important;        box-shadow: none !important;    }    #message {        width: 752px;        margin-bottom: 0;    }    .apiHeader {        background: #39464E url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>) repeat;        border-bottom: 3px solid rgba(0,0,0,0.25);        padding: 10px;        padding-top: 15px;        border-top-left-radius: 5px;        border-top-right-radius: 5px;        clear: both;    }    .apiHeader img {        width: 200px;        float: left;        margin-left: 10px;    }    .apiHeader a {        float: right;        display: block;        color: #fff;        font-size: 13px;        text-decoration: none;        margin-right: 5px !important;        background-color: rgba(0, 0, 0, .3);        border: 2px solid #2b2e30;        color: #afbbc8 !important;        font-weight: 700;        -webkit-border-radius: 4px;        -moz-border-radius: 4px;        border-radius: 4px;        padding: 7px;        padding-left: 12px;        padding-right: 12px;        margin-top: 0px !important;        text-transform: uppercase;        text-decoration: none;        font-size: 14px;    }    .apiHeader a:hover {        border: 2px solid #1D81C8;        background-color: rgba(0, 0, 0, .6);        color: #fff !important;    }    .apiSubHeader {        background-color: #0166AE;        background-image: url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>);        border-bottom: 3px solid rgba(0,0,0,0.25);        color: #fff;        padding: 15px 18px;    }    .apiSubHeader h2 {        margin: 0 !important;        padding: 0 !important;        color: #fff;        font-weight: bold;        font-size: 17px;        text-shadow: 1px 1px 0 #0367AE;    }    .apiSubHeader a.editThisPage {        float: right;        padding: 5px 10px;        color: #fff;        text-shadow: 1px 1px 0 #0367AE;        text-decoration: none;        padding-top: 15px;        margin-top: 6px;        font-size: 14px;        border: 2px solid #1D81C8;        background-color: #054B7C;        color: #fff !important;        font-weight: 700;        -webkit-border-radius: 4px;        -moz-border-radius: 4px;        border-radius: 4px;        padding: 7px;        padding-left: 12px;        padding-right: 12px;        text-transform: uppercase;        text-decoration: none;        font-size: 12px;    }    .apiSubHeader a.editThisPage i {        margin-right: 4px;    }    .apiSubHeader a.editThisPage:hover {        border: 2px solid #FFB700;        text-shadow: 1px 1px 0 #222;        background-color: rgba(0, 0, 0, .6);        color: #fff !important;    }    .apiSubHeader a{        text-shadow: 1px 1px 0 #0367AE;        color: #fff;        text-decoration: none;        font-size: 12px;        opacity: .8;    }    .apiSubHeader a:hover {        opacity: 1;    }    .inside {        padding: 0 !important;        margin-top: 0 !important;        border-radius: 5px !important;    }     .inside h2 {        display: block !important;     }    #postbox-container-2 {        margin-top: -20px !important;    }    body #poststuff .hndle.ui-sortable-handle {        display: none !important;    }    body #poststuff .handlediv {        display: none !important;    }    #loading {        float: left;        margin-left: 10px;        display: none;        margin-top: 6px;        color: #999;    }    #apiFooter {        background: #F1F1F1;        padding: 20px 0;        padding-bottom: 10px;    }    #apiFooter a:hover {        text-decoration: none;    }    .bootstrap-wp {        overflow: hidden;        border: 10px solid #F1F1F1;    }    .deleteButton {        padding: 5px;        float: left;        color: #777 !important;        font-size: 13px;    }    body .cf_header .btn {        font-size: 13px !important;        padding: 8px 15px;    }    body .btn-selected {        background-color: #0166AE !important;        background-image: url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>) !important;        text-shadow: 1px 1px 0 #034E82 !important;        font-family: Arial !important;        color: #ffffff !important;        border: solid #1f628d 1px !important;        text-decoration: none !important;        transition: none !important;    }    body label {        font-size: 11px;        opacity: .8;    }    body label i {        padding-left: 10px;    }    .helpinfo {        padding: 20px 40px;        background: #fafafa;        border-top: 1px solid #f2f2f2;        margin: 0 0px;        margin-top: -20px;        margin-bottom: -20px;    }    .helpinfo h4 {        color: #3B474F;        font-size: 15px;        font-weight: 700;        padding-bottom: 10px;        padding-top: 5px;    }    .helpinfo p {        color: #888;        font-weight: 300;        font-size: 12px;        line-height: 19px;        letter-spacing: 1px;        padding-bottom: 0;    }     .helpinfo h4 i {        margin-right: 4px;     }    #message {        width: 758px;        padding: 10px 10px;        display: block;        z-index: 99999;        position: relative;        color: #fff;        border: 1px solid #024D82;        border-bottom: 2px solid #024D82;        text-shadow: 1px 1px 0 #024D82;        padding: 0 10px;        font-size: 16px;        margin-bottom: 0;        border-radius: 5px;         background: #1069AC url(<?php echo plugins_url( 'geobg.png', __FILE__ ); ?>) repeat;    }    .noAPI {        padding: 10px;        border-bottom: 2px solid #c93f2b;        background: #E64D37;    }    .noAPI h4 {        margin: 0;        padding: 0;        color: #fff;    }     .noAPI h4 a {        color: #fff;        font-weight: normal;     }    .input-xlarge {            }    .control-group {        padding-left: 25px    }    #autosaving {        display: none;        width: 780px;        height: 310px;        position: absolute;        z-index: 2222;        top: 145px;        color: #fff;        left: 0;        padding-top: 160px;        text-align: center;        border-bottom-right-radius: 5px;        border-bottom-left-radius: 5px;        background: rgba(0, 0, 0, .8);    }        #autosaving h2 {            color: #fff;            font-weight: 600;            text-shadow: 0 0 2p            x #000;            margin-top: 0;        }        #autosaving h4 {            color: #fff;            font-size: 13px;            letter-spacing: 1px;            font-weight: 300;            margin-bottom: 5px;            text-shadow: 0 0 2px #000;        }        #autosaving h1 {            color: #fff;            margin-top: 0;            font-size: 58px;            font-weight: 800;            text-shadow: 0 0 2px #000;        }</style><?phpif ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {}else {    if ( $cf_page != "" ) {        $json = cf_get_file_contents( 'https://api.clickfunnels.com/funnels/'.$cf_thefunnel.'.json?email='.get_option( 'clickfunnels_api_email' ).'&auth_token='.get_option( 'clickfunnels_api_auth' ) );        $cf_funnels_pages = json_decode( $json );    }}?><div id="autosaving">    <h4><i class="fa fa-times"></i> Uh oh, your page did not save correctly.</h4>    <h2>Saving and Reloading...</h2>    <h1><i class="fa fa-cog fa-spin"></i></h1></div><div class="apiHeader">    <img src="<?php echo plugins_url( 'logo.png', __FILE__ ); ?>" alt="">    <a href="https://www.clickfunnels.com/users/sign_in" target="_blank" class=""><i class="fa fa-bars"></i> My Account</a>    <br clear="all"></div><div class="apiSubHeader">    <?php if ( !empty( $_GET['action'] ) ) {  ?>    <?php if ( $cf_type=='p' ) {?>    <a style="margin-right: -3px;" href="https://www.clickfunnels.com/pages/<?php echo $thepage[2]; ?>" target="_blank" class="editThisPage"><i class="fa fa-edit"></i> Launch in Editor</a>    <a style="margin-right: 10px;" href="https://www.clickfunnels.com/funnels/<?php echo $thepage[0]; ?>#<?php echo $thepage[3]; ?>" target="_blank" class="editThisPage"><i class="fa fa-cogs"></i> Edit Funnel</a>    <a style="margin-right: 10px;" href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/<?php echo $cf_slug; ?>" title="View Page" target="_blank" class="editThisPage"><i class="fa fa-search"></i> View Page</a>    <h2><?php foreach ( $cf_funnels as $key=>$funnel ) {  ?>    <?php if ( $cf_thefunnel == $funnel->id ) { echo $funnel->name; } } ?></h2>        <a href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/<?php echo $cf_slug; ?>" title="View Page" target="_blank"> <?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/<?php echo $cf_slug; ?></a>    <?php }?>    <?php if ( $cf_type=='hp' ) {?>     <a href="https://www.clickfunnels.com/pages/<?php echo $thepage[2]; ?>" target="_blank" class="editThisPage"><i class="fa fa-edit"></i> Launch in Editor</a>    <a style="margin-right: 10px;" href="https://www.clickfunnels.com/funnels/<?php echo $thepage[0]; ?>#<?php echo $thepage[3]; ?>" target="_blank" class="editThisPage"><i class="fa fa-cogs"></i> Edit Funnel</a>    <a style="margin-right: 10px;" href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/<?php echo $cf_slug; ?>" title="View Page" target="_blank" class="editThisPage"><i class="fa fa-search"></i> View Page</a>        <h2>Set as Home Page</h2>        <a href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>" title="View Page" target="_blank"><i class="fa fa-search"></i>  <?php echo get_option( 'clickfunnels_siteURL' ) ; ?></a>    <?php }?>    <?php if ( $cf_type=='np' ) {?>     <a href="https://www.clickfunnels.com/pages/<?php echo $thepage[2]; ?>" target="_blank" class="editThisPage"><i class="fa fa-edit"></i> Launch in Editor</a>    <a style="margin-right: 10px;" href="https://www.clickfunnels.com/funnels/<?php echo $thepage[0]; ?>#<?php echo $thepage[3]; ?>" target="_blank" class="editThisPage"><i class="fa fa-cogs"></i> Edit Funnel</a>    <a style="margin-right: 10px;" href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/<?php echo $cf_slug; ?>/invalid-url-404-testing" title="View Page" target="_blank" class="editThisPage"><i class="fa fa-search"></i> View Page</a>        <h2>Set as 404 Page</h2>        <a href="<?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/invalid-url" title="View Page" target="_blank"><i class="fa fa-search"></i>  <?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/invalid-url</a>    <?php }?>    <?php if ( $cf_type=='' ) {?>        <h2>Page Undefined - Create New Page</h2>    <?php }?>    <?php } else { ?>        <h2 style="font-size: 17px;">Add ClickFunnels Page to Your Blog</h2>    <?php } ?></div><?php if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) { ?><div class="noAPI">    <h4>You haven't setup your API settings. <a href="../wp-admin/edit.php?post_type=clickfunnels&page=cf_api">Click here to setup now.</a></h4></div><?php } ?><form method="post">    <div class="bootstrap-wp" style=" border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;"><?php wp_nonce_field( "save_clickfunnel", "clickfunnel_nonce" ); ?>        <div class="row-fluid form-horizontal">            <br>            <div class="control-group" style="margin-left: 0px; margin-bottom: 20px;">                <div data-target="cf_type"  class="btn-group multichoice cf_header">                    <a data-value="p" class="btn <?php if ( $cf_type=='p' ) {?> active btn-selected<?php }?>">Regular Page</a>                    <a data-value="hp" class="btn <?php if ( $cf_type=='hp' ) {?> active btn-selected<?php }?>">Home Page</a>                    <a data-value="np" class="btn <?php if ( $cf_type=='np' ) {?> active btn-selected<?php }?>">404 Page</a>                </div>                <input type="hidden" id="cf_type" name="cf_type"/>            </div>            <br>            <div class="control-group">                <label class="control-label" for="cf_thefunnel"> Choose Funnel  <i class="fa fa-caret-square-o-down"></i></label>                <div class="controls">                    <select class="input-xlarge" id="cf_thefunnel" name="cf_thefunnel_backup">                        <?php if ( empty( $cf_funnels ) ) { ?>                        <option value="0">No Funnels Found</option>                        <?php }                            else {                                foreach ( $cf_funnels as $key=>$funnel ) {  ?>                                <option value="<?php echo $funnel->id;?>" <?php if ( $cf_thefunnel == $funnel->id ) { echo "selected"; } ?>><?php echo $funnel->name;?></option>                            <?php                                }                            } ?>                    </select>                </div>            </div>            <div class="control-group">                <label class="control-label" for="cf_thepage"> Choose Page  <i class="fa fa-file-o"></i></label>                <div class="controls">                    <select class="input-xlarge" id="cf_thepage" name="cf_thepage" style="float: left;">                        <?php if ( empty( $cf_funnels_pages ) ) { ?>                        <option value="0">No Pages Found</option>                        <?php }                        else {                            foreach ( $cf_funnels_pages->funnel_steps as $key => $funnel ) {  ?>                                <option value="<?php echo $funnel->position;?>" <?php if ( $cf_thepage == $funnel->position ) { echo "selected"; } ?>><?php echo $funnel->name;?></option>                            <?php                            }                        }                        ?>                    </select>                    <i class="fa fa-spinner fa-spin" id="loading"></i>                </div>                <div id="noPageWarning" style="font-size: 11px; margin-left: 160px;padding-top: 10px;display: none;width: 100%; clear: both"><em>You have no pages for this funnel.  <a href="https://www.clickfunnels.com/funnels/<?php echo $thepage[0]; ?>" target="_blank">Click to add new page.</a></em></div>            </div>            <div class="control-group" id="hiddenStuff" style="display: none">                <div class="controls">                    <strong>Funnel ID: <span style="font-weight: normal"><?php echo $thepage[0]; ?></span> <br></strong>                    <strong>Page Key: <span style="font-weight: normal"><?php echo $thepage[1]; ?></span><br></strong>                    <strong>Page ID: <span style="font-weight: normal"><?php echo $thepage[2]; ?></span><br></strong>                    <strong>Funnel Step ID: <span style="font-weight: normal"><?php echo $thepage[3]; ?></span><br></strong>                    <strong>Meta: <span style="font-weight: normal"><?php echo $thepage[4]; ?></span><br></strong>                    <strong>Post ID: <span style="font-weight: normal"><?php echo $thepage[5]; ?></span><br></strong>                    <input type="text" name="cf_thefunnel" id="cf_data" value="<?php echo $savedData; ?>" style="width: 400px; padding: 7px; height: 50px; font-size: 18px;" />                </div>            </div>            <?php if ( $cf_type!="p" ) $display ="display:none"; else $display="";?>            <div class="cf_url control-group" style="<?php echo $display;?>"  >                <label class="control-label" for="cf_slug"> Custom URL <i class="fa fa-external-link"></i></label>                <div id="cf-wp-path" class="controls ">                    <div class="input-prepend">                        <span class="add-on" style="font-size: 13px;padding: 4px 10px; background: #ECEEEF; color: #777; text-shadow: none; border: 1px solid #ccc"><?php echo get_option( 'clickfunnels_siteURL' ) ; ?>/</span><input style="height:28px;width: 150px !important" type="textbox" value="<?php if ( isset( $cf_slug ) ) echo $cf_slug;?>" name="cf_slug" id="cf_slug" class="input-xlarge">                        <div style="color:red; display:none" id="cf_invalid_slug" style="width: 90%" >You must enter an URL</div>                    </div>                </div>            </div>            <br><br>            <div class="p_text helpinfo" <?php if ( $cf_type!='p' ) {?> style="display: none" <?php }?>>                <h4><i class="fa fa-question-circle"></i> Set as a Page</h4>                <p>Choose any ClickFunnels page to be shown using a custom URL just select any funnel to refresh the list of pages. Create a custom URL and hit 'Save/Publish' to start sending traffic. <a href="<?php echo admin_url( 'edit.php?post_type=clickfunnels&page=clickfunnels_support' );?>">Need more help?</a> </p>                <p style="font-size: 11px;opacity: .7"><i class="fa fa-exclamation-triangle"></i> Changes made to the page in editor may take up to 30 seconds to appear on Wordpress page.</p>            </div>            <div class="hp_text helpinfo" <?php if ( $cf_type!='hp' ) {?> style="display: none" <?php }?>>                <h4><i class="fa fa-question-circle"></i> Set as Home Page</h4>                <p> Replace your homepage with a specific ClickFunnels page. You can show any page that you want, this will replace any other homepage settings you may have.</p>                <p style="font-size: 11px;opacity: .7"><i class="fa fa-exclamation-triangle"></i> Changes made to the page in editor may take up to 30 seconds to appear on Wordpress page.</p>            </div>            <div class="np_text helpinfo" <?php if ( $cf_type!='np' ) {?> style="display: none" <?php }?>>                <h4><i class="fa fa-question-circle"></i> Set as 404 Page</h4>                <p> Show a specific page to be shown on any "Page not Found" such as a misspelled URL or a deleted blog post. Very good place for a squeeze page to get the most out of your traffic.</p>                <p style="font-size: 11px;opacity: .7"><i class="fa fa-exclamation-triangle"></i> Changes made to the page in editor may take up to 30 seconds to appear on Wordpress page.</p>            </div>            <br>            <div class="row-fluid" id="apiFooter">                <?php if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) { ?>                    <button id="publish" name="publish" disabled class="button button-primary " style="float: right;">                    <?php if ( !empty( $_GET['action'] ) ) {  echo "<i class='fa fa-save'></i> Save Changes"; } else { echo "<i class='fa fa-save'></i> Publish Page"; } ?>                    </button>                    <?php } else { ?>                    <button id="publish" name="publish" class="button button-primary " style="float: right;">                    <?php if ( !empty( $_GET['action'] ) ) {  echo "<i class='fa fa-save'></i> Save Changes"; } else { echo "<i class='fa fa-save'></i> Publish Page"; } ?>                    </button>                    <?php } ?>                <div id="saving" style="float: right;display: none; padding-right: 10px;opacity: .6;padding-top: 5px;">                     <i class="fa fa-spinner fa-spin"></i>                     <span>Saving...</span>                </div>                <a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'edit.php?post_type=clickfunnels' );?>"><i class="fa fa-file-text-o"></i> Pages</a>                <a class="button button-secondary" style="float: left; margin-right: 10px" type="submit" href="<?php echo admin_url( 'edit.php?post_type=clickfunnels&page=cf_api' );?>"><i class="fa fa-cog"></i> Settings</a>                <a href="<?php echo admin_url( 'edit.php?post_type=clickfunnels&page=clickfunnels_support' );?>" class="button button-default" style="float: left;margin-right: 10px"><i class="fa fa-life-ring"></i> Support</a>                <?php if ( !empty( $delete_link ) ) {?>                <a class="button button-secondary" id="delete" type="submit" href="<?php echo $delete_link;?>"><i class="fa fa-trash"></i> Delete Page</a>                <?php }?>            </div>        </div>    </div></form><?php include('footer.php'); ?><script>    (function($) {        setTimeout(function() {            $('#cf_thepage').trigger( "change" );        }, 1500);    })(jQuery);</script>