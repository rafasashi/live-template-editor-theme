//////----TO TOP---////////
jQuery(document).ready(function ($) {
	jQuery('.totop').click(function() {
		jQuery('html, body').animate( {
			scrollTop: 0
		}
		, "slow");
	}
	);
}
);

//////----MENU---////////
jQuery(document).ready(function ($) {
	$('.navbar .dropdown').hover(function() {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(200).slideDown();
	}
	, function() {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).slideUp('fast', function() {
			na.removeClass('extra-nav-class')
		}
		)
	}
	);
	$('.dropdown-submenu').hover(function() {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(200).slideDown();
	}
	, function() {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).slideUp('fast', function() {
			na.removeClass('extra-nav-class')
		}
		)
	}
	);
}
);
/*! http://tinynav.viljamis.com v1.1 by @viljamis */
(function(a,i,g) {
	a.fn.tinyNav=function(j) {
		var b=a.extend( {
			active:"selected",header:"",label:""
		}
		,j);
		return this.each(function() {
			g++;
			var h=a(this),d="tinynav"+g,f=".l_"+d,e=a("<select/>").attr("id",d).addClass("tinynav "+d);
			if(h.is("ul,ol")) {
				""!==b.header&&e.append(a("<option/>").text(b.header));
				var c="";
				h.addClass("l_"+d).find("a").each(function() {
					c+='<option value="'+a(this).attr("href")+'">';
					var b;
					for (b=0;b<a(this).parents("ul, ol").length-1;b++)c+="- ";
					c+=a(this).text()+"</option>"
				}
				);
				e.append(c);
				b.header||e.find(":eq("+a(f+" li").index(a(f+" li."+b.active))+")").attr("selected",!0);
				e.change(function() {
					i.location.href=a(this).val()
				}
				);
				a(f).after(e);
				b.label&&e.before(a("<label/>").attr("for",d).addClass("tinynav_label "+d+"_label").append(b.label))
			}
		}
		)
	}
}
)(jQuery,this,0);

//////----Tiny Nav Responsive Menu---////////
jQuery(document).ready(function ($) {
	$("#main-menu").tinyNav( {
		active: 'selected', // String: Set the "active" class
		header: 'NAVIGATION', // String: Specify text for "header" and show header instead of the active item
		label: '' // String: Sets the <label> text for the <select> (if not set, no label will be added)
	}
	);
}
);