jQuery(document).ready(function(){var a,e=jQuery(".fusion-patcher-table");_.each(patcherVars.args,function(a){var e,t,p;void 0!==patcherVars.patches[a.context]&&0<patcherVars.patches[a.context]&&"none"!==patcherVars.display_counter&&(e=jQuery("#adminmenu .toplevel_page_"+a.parent_slug+" .wp-menu-name"),t=jQuery("#adminmenu .toplevel_page_"+a.parent_slug+' ul.wp-submenu li a[href="admin.php?page='+a.context+'-patcher"]'),p='<span class="avada-patches-count update-plugins count-'+patcherVars.patches[a.context]+'" style="background-color:#65bc7b;margin-left:5px;"><span class="plugin-count">'+patcherVars.patches[a.context]+"</span></span>","sub_level"!==patcherVars.display_counter&&jQuery(p).appendTo(e),"top_level"!==patcherVars.display_counter&&(jQuery(p).appendTo(t),jQuery(p).appendTo(jQuery(".avada-db-menu-sub-item-patcher .avada-db-menu-sub-item-label"))),jQuery(".avada-db-maintenance-counter").show())}),jQuery(".fusion-patcher-table").on("click",".awb-patch-applied-icon",function(a){a.preventDefault(),jQuery(this).siblings(".button.button-primary").trigger("click")}),jQuery("#bulk-apply-patches").on("click",function(t){var p=[];t&&t.preventDefault(),jQuery.each(jQuery(".fusion-patcher-table-head:not(.awb-patch-applied)"),function(a,e){p.push(parseInt(jQuery(e).data("patch-id")))}),0!==p.length&&(e.addClass("awb-bulk-applying-patches"),a(p))}),a=function(t){var p;if(0===t.length)return e.removeClass("awb-bulk-applying-patches"),void jQuery("#bulk-apply-patches").fadeOut();(p=jQuery('.fusion-patcher-table-head:not(.awb-patch-applied)[data-patch-id="'+t[0]+'"]')).addClass("awb-patch-applying"),jQuery.ajax({type:"POST",url:ajaxurl,dataType:"json",data:{action:"awb_apply_patch",patchID:t[0],awb_patcher_nonce:jQuery("#awb-bulk-patches-nonce").val()}}).done(function(n){p.removeClass("awb-patch-applying"),!0===n.success?(p.removeClass("awb-patch-failed").addClass("awb-patch-applied"),0<p.find(".patch-apply .button.button-primary").length?p.find(".patch-apply .button.button-primary").val(patcherVars.patch_applied_text).after('<span class="awb-patch-applied-icon"><i class="fusiona-checkmark"></i></span>'):p.find(".patch-apply .button").removeClass("disabled").addClass("button-primary").html(patcherVars.patch_applied_text).after('<span class="awb-patch-applied-icon"><i class="fusiona-checkmark"></i></span>'),t.shift(),a(t)):(p.addClass("awb-patch-failed"),0<p.find(".patch-apply .button.button-primary").length&&0===p.find(".dismiss-notices").length&&p.find(".patch-apply .button.button-primary").after('<span class="dismiss-notices"><a class=" fusiona-times-solid" href="'+patcherVars.admin_url+"admin.php?page=avada-patcher&manually-applied-patch="+n.data.patch_id+'" title="'+patcherVars.patch_dismiss_notice_text+'"></a><span>'),e.removeClass("awb-bulk-applying-patches"),jQuery("#bulk-apply-patches").fadeOut())})}});