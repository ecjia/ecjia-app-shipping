// JavaScript Document

;(function(app, $) {
	app.express = {
		info : function() {
			app.express.expressForm();
			app.express.choose_area();
			app.express.selected_area();
			app.express.quick_search();
			app.express.delete_area();
			app.express.close_model();
			app.express.add_shipping();
			app.express.shippingForm();
		},
		expressForm : function() {
			$("form[name='expressForm']").on('submit', function(e){
				e.preventDefault();
				$(this).ajaxSubmit({
					dataType:"json",
					success:function(data){
						ecjia.merchant.showmessage(data);
					}
				});
			});
			
            var $form = $("form[name='theForm']");
            var option = {
                rules: {
                	template_name: {
                        required: true,
                    }
                },
                messages: {
                	template_name: {
                        required: '请填写模版名称',
                    }
                },
                submitHandler: function () {
                    $form.ajaxSubmit({
                        dataType: "json",
                        success: function (data) {
                        	ecjia.merchant.showmessage(data);
                        	return false;
                        }
                    });
                }
            }
            var options = $.extend(ecjia.merchant.defaultOptions.validate, option);
            $form.validate(options);
		},
		
		choose_area: function () {
            $('.ms-elem-selectable').off('click').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    val = $this.attr('data-val'),
                    url = $this.parent().attr('data-url'),
                    $next = $('.' + $this.parent().attr('data-next'));
                	$next_attr = $this.parent().attr('data-next');
                /* 如果是县乡级别的，不触发后续操作 */
                if ($this.parent().hasClass('selStreets')) {
                    $this.siblings().removeClass('disabled');
                    if (val != 0) $this.addClass('disabled');
                    return;
                }
                /* 如果是0的选项，则后续参数也设置为0 */
                if (val == 0) {
                    var $tmp = $('<li class="ms-elem-selectable" data-val="0"><span>没有可选择的地区</span></li>');
                    $next.html($tmp);
                    $tmp.trigger('click');
                    return;
                }
                /* 请求参数 */
                $.get(url, {
                    parent: val
                }, function (data) {
                    $this.siblings().removeClass('disabled');
                    $this.addClass('disabled');
                    var html = '';
                    /* 如果有返回参数，则赋值并触发下一级别的选中 */
                    if (data.regions) {
                    	var region = [];
	                	$('.select-region li').each(function() {
	                		region.push($(this).find('input').val());
	                	});
	                	for (var i = 0; i <= data.regions.length - 1; i++) {
	                		html += '<li class="ms-elem-selectable select_hot_city" data-val="' + data.regions[i].region_id + '"><span>' +
                            data.regions[i].region_name + '</span>';
	                		var region_id = data.regions[i].region_id;
	                		var index = $.inArray(region_id, region);
	                		if ($next_attr == 'selCities' && index == -1) {
	                			html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                          	}
                        	if ($next_attr == 'selDistricts' && index == -1) {
                             	html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                            }
                            if ($next_attr == 'selStreets' && index == -1) {
                                html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                            }
                            html += '</li>';
                          };
                        $next.html(html);
                        app.express.quick_search();
                        app.express.choose_area();
                        app.express.selected_area();
                    } else {
                        var $tmp = $('<li class="ms-elem-selectable" data-val="0"><span>没有可选择的地区</span></li>');
                        $next.html($tmp);
                        $tmp.trigger('click');
                        return;
                    }
                }, 'json');
            });
        },
 
        selected_area: function () {
            $('.ms-elem-selectable .edit-list a').off('click').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var bool = true;
                var $this = $(this),
                    $parent = $this.parents('li.ms-elem-selectable'),
                    val = $parent.attr('data-val'),
                    name = $parent.find('span').eq(0).text(),
                    $tmp = $('<li><input type="hidden" value="' + val + '" name="regions[]" id="regions_' + val + '"/>'+ name +'<span class="delete_area">x</span></li>');
                $('.select-region input').each(function (i) {
                    if ($(this).val() == val) {
                        bool = false;
                        return false;
                    }
                });
                if (bool) {
                	$this.hide();
                    $('.select-region').append($tmp);
                    app.express.delete_area();
                }
            });
        },
 
        quick_search: function () {
            var opt = {
                onAfter: function () {
                    $('.ms-group').each(function (index) {
                        $(this).find('.isShow').length ? $(this).css('display', 'block') : $(this).css('display', 'none');
                    });
                    return;
                },
                show: function () {
                    this.style.display = "";
                    $(this).addClass('isShow');
                },
                hide: function () {
                    this.style.display = "none";
                    $(this).removeClass('isShow');
                },
            };
            $('#selCountry').quicksearch($('.selCountry .ms-elem-selectable'), opt);
            $('#selProvinces').quicksearch($('.selProvinces .ms-elem-selectable'), opt);
            $('#selCities').quicksearch($('.selCities .ms-elem-selectable'), opt);
            $('#selDistricts').quicksearch($('.selDistricts .ms-elem-selectable'), opt);
            $('#selStreets').quicksearch($('.selStreets .ms-elem-selectable'), opt);
        },
        
        delete_area: function() {
        	$('.delete_area').off('click').on('click', function() {
        		var $this = $(this);
        		var val = $this.parent('li').find('input').val();
        		$('.ms-elem-selectable').each(function() {
        			if ($(this).attr('data-val') == val) {
        				$(this).find('a').show();
        			}
        		});
        		$this.parent('li').remove();
        	});
        },
        
        close_model: function() {
        	$('#chooseRegion').on('show.bs.modal', function () {
        		var child = $('.content-area-list').html();
        		var length = $('.content-area-list').find('li').length;
        		if (length > 0) {
            		$('.select-region').html('').html(child);
            		var region = [];
            		$('.select-region li').each(function() {
            			$(this).append('<span class="delete_area">x</span>');
            			region.push($(this).find('input').val());
            		});
            		app.express.delete_area();
            		
            		$('.ms-elem-selectable').each(function() {
            			var val = $(this).attr('data-val');
            			var index = $.inArray(val, region);
            			if (index != -1) {
            				$(this).find('a').hide();
            			}
            		});
        		} else {
        			$('.ms-elem-selectable').each(function() {
            			$(this).find('a').show();
            		});
        		}
        	});
        		
        	$('.close_model').off('click').on('click', function() {
        		var $this = $(this);
        		var region = $('.select-region').children();
        		if (region.length > 0) {
        			$('.add_area').hide();
        			$('.content-area').show();
        			$('.content-area-list').show();
        			$('.content-area-list').html(region);
        			$('.content-area-list').find('.delete_area').remove();
        		} else {
        			$('.add_area').show();
        			$('.content-area').hide();
        			$('.content-area-list').hide();
        			$('.content-area-list').html('');
        		}
        	});
        	
        	$('.reset_region').off('click').on('click', function() {
				$('.add_area').show();
    			$('.content-area').hide();
    			$('.content-area-list').hide();
    			$('.content-area-list').html('');
        	});
        },
        
        add_shipping: function() {
        	$('select[name="shipping_id"]').off('change').on('change', function() {
        		$('#shipping_info').html('');
        		var $this = $(this),
        			val = $this.val(),
        			url = $this.attr('data-url'),
        			fee_compute_mode = 'by_weight',
        			type = $('.add-shipping-btn').attr('data-type');
        		var shipping_item = $('.template-info-item').find('.shipping-item-' + val);
        		if (type == 'edit') {
	        		if (shipping_item.length > 0) {
	        			var fee_compute_mode = shipping_item.find("input[name='fee_compute_mode']").val(),
	        				item_fee = shipping_item.find('input[name="item_fee"]').val(),
	        				pay_fee = shipping_item.find('input[name="pay_fee"]').val(),
	        				base_fee = shipping_item.find('input[name="base_fee"]').val(),
	        				free_money = shipping_item.find('input[name="free_money"]').val(),
	        				step_fee = shipping_item.find('input[name="step_fee"]').val();
	        		}
        		}
        		if (val > 0) {
        			var shipping_area_id = $('input[name="shipping_area_id"]').val();
        			var shipping = $('input[name="shipping"]').val();
        			$.post(url, {'shipping_id': val, 'shipping_area_id': shipping_area_id, 'shipping': shipping}, function(data) {
        				var shipping_code = data.shipping_area.shipping_code;
        				var html = '';
        				var arr = ['ship_ems', 'ship_yto', 'ship_zto', 'ship_sto_express', 'ship_post_mail', 'ship_sf_express', 'ship_post_express'];
        				if (data.content.length > 0 && shipping_code != 'ship_cac') {
        					var content = data.content;
        					for (var i = 0; i <= content.length - 1; i++) {
        						if ($.inArray(shipping_code, arr) != -1 && content[i].name == 'fee_compute_mode') {
        							html += '<div class="form-group" id='+ content[i].name +'>';
        							html += '<label class="control-label col-lg-4">'+ content[i].label+ '</label>';
        							if (content[i].value == 'by_weight') {
        								html += '<div class="controls col-lg-6"><input type="radio" id="fee_compute_mode_by_weight" name="fee_compute_mode" checked value="by_weight" data-code="'+ shipping_code +'"/>';
        							} else {
        								html += '<div class="controls col-lg-6"><input type="radio" id="fee_compute_mode_by_weight" name="fee_compute_mode" value="by_weight" data-code="'+ shipping_code +'"/>';
        							}
                					html += '<label for="fee_compute_mode_by_weight">按重量</label>';
                					if (content[i].value == 'by_number') {
                						html += '<input type="radio" id="fee_compute_mode_by_number" name="fee_compute_mode" checked value="by_number" data-code="'+ shipping_code +'"/>';
                					} else {
                						html += '<input type="radio" id="fee_compute_mode_by_number" name="fee_compute_mode" value="by_number" data-code="'+ shipping_code +'"/>';
                					}
                					html += '<label for="fee_compute_mode_by_number">按件数</label>';
                					html += '</div></div>';
        						}
        						if (fee_compute_mode == 'by_number') {
	        						if (content[i].name == 'item_fee' || content[i].name == 'free_money' || content[i].name == 'pay_fee') {
	        							if (content[i].name == 'item_fee') {
	        								content[i].value = item_fee != undefined ? item_fee : content[i].value;
	        							}
	        							if (content[i].name == 'free_money') {
	        								content[i].value = free_money != undefined ? free_money : content[i].value;
	        							}
	        							if (content[i].name == 'pay_fee') {
	        								content[i].value = pay_fee != undefined ? pay_fee : content[i].value;
	        							}
	        							html += '<div class="form-group" id='+ content[i].name +'>';
	        							html += '<label class="control-label col-lg-4">'+ content[i].label+ '</label>';
	        							html += '<div class="controls col-lg-6"><input class="form-control" name="'+ content[i].name +'" type="text" value="'+ content[i].value +'"/></div><span class="input-must">*</span>';
	        							html += '</div>';
	        						} else if (content[i].name != 'fee_compute_mode') {
	        							if (content[i].name == 'base_fee') {
	        								content[i].value = base_fee != undefined ? base_fee : content[i].value;
	        							}
	        							if (content[i].name == 'step_fee') {
	        								content[i].value = step_fee != undefined ? step_fee : content[i].value;
	        							}
	        							html += '<div class="form-group" id='+ content[i].name +' style="display:none;">';
	        							html += '<label class="control-label col-lg-4">'+ content[i].label+ '</label>';
	        							html += '<div class="controls col-lg-6"><input class="form-control" name="'+ content[i].name +'" type="text" value="'+ content[i].value +'" disabled/></div><span class="input-must">*</span>';
	        							html += '</div>';
	        						}
        						} else {
        							if (content[i].name != 'item_fee' && content[i].name != 'fee_compute_mode') {
	        							if (content[i].name == 'free_money') {
	        								content[i].value = free_money != undefined ? free_money : content[i].value;
	        							}
	        							if (content[i].name == 'pay_fee') {
	        								content[i].value = pay_fee != undefined ? pay_fee : content[i].value;
	        							}
	        							if (content[i].name == 'base_fee') {
	        								content[i].value = base_fee != undefined ? base_fee : content[i].value;
	        							}
	        							if (content[i].name == 'step_fee') {
	        								content[i].value = step_fee != undefined ? step_fee : content[i].value;
	        							}
	        							html += '<div class="form-group" id='+ content[i].name +'>';
	        							html += '<label class="control-label col-lg-4">'+ content[i].label+ '</label>';
	        							html += '<div class="controls col-lg-6"><input class="form-control" name="'+ content[i].name +'" type="text" value="'+ content[i].value +'"/></div><span class="input-must">*</span>';
	        							html += '</div>';
	        						} else {
	        							if (content[i].name == 'item_fee') {
	        								content[i].value = item_fee != undefined ? item_fee : content[i].value;
	        							}
	        							html += '<div class="form-group" id='+ content[i].name +' style="display:none;">';
	        							html += '<label class="control-label col-lg-4">'+ content[i].label+ '</label>';
	        							html += '<div class="controls col-lg-6"><input class="form-control" name="'+ content[i].name +'" type="text" value="'+ content[i].value +'" disabled/></div><span class="input-must">*</span>';
	        							html += '</div>';
	        						}
        						}
        					}
        				}
        				$('#shipping_info').append(html);
        				app.express.area_compute_mode();
        			});
        		}
        	});
        	
        	$('.add_shipping').off('click').on('click', function() {
        		var template_name = $('input[name="temp_name"]').val();
        		if (template_name == '') {
        			smoke.alert('请输入模版名称');
        			return false;
        		}
        		
        		var length = $('.content-area-list').find('input').length;
        		if (length == 0) {
        			smoke.alert('请选择地区');
        			return false;
        		}
        		clearForm();
        		$('.add-shipping-btn').attr('data-type', 'add');
        		$('form[name="shippingForm"]').find('input[name="regions[]"]').remove();
        		
        		var shipping_name = $('form[name="theForm"]').find('input[name="temp_name"]').val();
            	$('form[name="shippingForm"]').find('input[name="temp_name"]').val(shipping_name);
            	$('form[name="shippingForm"]').find('input[name="shipping_area_id"]').val(0);
            	
            	var $temp = $('form[name="theForm"]').find('input[name="regions[]"]');
            	$('form[name="shippingForm"]').append($temp.clone(true));
            	
        		$('#addShipping').modal('show');
        	});
        	
        	$('.remove_shipping').off('click').on('click', function() {
        		var message = '您确定要删除该快递方式吗？';
        		var $this = $(this);
				smoke.confirm(message, function(e) {
					if (e) {
						$this.parents('.info-shipping-item').remove();
					}
				}, {ok:"确定", cancel:"取消"});
        	});
        	
        	$('.edit_shipping').off('click').on('click', function() {
        		var length = $('.content-area-list').find('input').length;
        		if (length == 0) {
        			smoke.alert('请选择地区');
        			return false;
        		}
        		
        		var $this = $(this),
        			shipping_id = $this.attr('data-shipping'),
        			shipping_area_id = $this.attr('data-area');
        		$('.add-shipping-btn').attr('data-type', 'edit');
        		$('form[name="shippingForm"]').find('input[name="regions[]"]').remove();
        		
        		var shipping_name = $('form[name="theForm"]').find('input[name="temp_name"]').val();
            	$('form[name="shippingForm"]').find('input[name="temp_name"]').val(shipping_name);
        		$('form[name="shippingForm"]').find('input[name="shipping_area_id"]').val(shipping_area_id);
        		$('form[name="shippingForm"]').find('input[name="shipping"]').val(shipping_id);
        		
        		var $temp = $('form[name="theForm"]').find('input[name="regions[]"]');
            	$('form[name="shippingForm"]').append($temp.clone(true));
            	
            	$('select[name="shipping_id"] option[value='+ shipping_id +']').attr('selected', true);
        		$('select[name="shipping_id"]').trigger("liszt:updated").trigger("change");
        		
        		$('#addShipping').modal('show');
        	});
        },
        
        /* 配送费用计算方式 */
        area_compute_mode: function () {
        	$('input[name="fee_compute_mode"]').off('click').on('click', function() {
        		var base_fee = document.getElementById("base_fee");
                var step_fee = document.getElementById("step_fee");
                var item_fee = document.getElementById("item_fee");
                var $this = $(this),
                	shipping_code = $this.attr('data-code'),
                 	mode = $this.val();
                
                if (shipping_code == 'ship_post_mail' || shipping_code == 'ship_post_express') {
                    var step_fee1 = document.getElementById("step_fee1");
                }
                if (mode == 'by_number') {
                    item_fee.style.display = '';
                    base_fee.style.display = 'none';
                    step_fee.style.display = 'none';
                    $('#item_fee').find('input').removeAttr('disabled');
                    $('#base_fee').find('input').attr('disabled', true);
                   	$('#step_fee').find('input').attr('disabled', true);
                    if (shipping_code == 'ship_post_mail' || shipping_code == 'ship_post_express') {
                    	step_fee1.style.display = 'none';
                    	$('#step_fee1').find('input').attr('disabled', true);
                    }
                } else {
                  	item_fee.style.display = 'none';
                   	base_fee.style.display = '';
                   	step_fee.style.display = '';
                   	$('#item_fee').find('input').attr('disabled', true);
                   	$('#base_fee').find('input').removeAttr('disabled');
                   	$('#step_fee').find('input').removeAttr('disabled');
                    if (shipping_code == 'ship_post_mail' || shipping_code == 'ship_post_express') {
                    	step_fee1.style.display = '';
                    	$('#step_fee1').find('input').removeAttr('disabled');
                    }
              	}
        	});
        },
        
        shippingForm: function () {
            var $form = $("form[name='shippingForm']");
            var option = {
                rules: {
                	shipping_id: {
                        required: true,
                        min: 0
                    },
                    base_fee: {
                    	min: 0
                    },
                    step_fee: {
                    	min: 0
                    },
                    free_money: {
                    	min: 0
                    },
                    pay_fee: {
                    	min: 0
                    },
                    item_fee: {
                    	min: 0
                    }
                },
                messages: {
                	shipping_id: {
                        required: '请选择快递方式',
                        min: '请选择快递方式'
                    },
                    base_fee: {
                    	min: '请输入正确的价格格式',
                    },
                    step_fee: {
                    	min: '请输入正确的价格格式',
                    },
                    free_money: {
                    	min: '请输入正确的价格格式',
                    },
                    pay_fee: {
                    	min: '请输入正确的价格格式',
                    },
                    item_fee: {
                    	min: '请输入正确的价格格式',
                    }
                },
                submitHandler: function () {
                	var val = $('select[name="shipping_id"]').val();
                	var type = $('.add-shipping-btn').attr('data-type');
                	var shipping_item = $('.template-info-item').find('.shipping-item-' + val);
                	if (type == 'add') {
             			if (shipping_item.length > 0) {
             				$('#addShipping').modal('hide');
             				var data = {
         						message : "该快递方式已存在",
         						state : "error",
         					};
             				ecjia.merchant.showmessage(data);
             				return false;
             			}
             		}
                    $form.ajaxSubmit({
                        dataType: "json",
                        success: function (data) {
                        	$('#addShipping').modal('hide');
                        	ecjia.merchant.showmessage(data);
                        	return false;
                        }
                    });
                }
            }
            var options = $.extend(ecjia.merchant.defaultOptions.validate, option);
            $form.validate(options);
        }
	}
	
	function clearForm() {
		$('select[name="shipping_id"] option').eq(0).attr("selected", true);
		$('select[name="shipping_id"]').trigger("liszt:updated").trigger("change");
		$('#shipping_info').html('');
	};

	
})(ecjia.merchant, jQuery);

// end