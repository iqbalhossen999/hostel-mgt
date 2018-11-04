<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="description" content="">
	<meta name="Keywords" content="">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/template.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script type="text/javascript" language="javascript" src="JS/jquery-1.2.1.min.js"></script>
	<script type="text/javascript" language="javascript" src="JS/menu-collapsed.js"></script>
	<script type="text/javascript" language="javascript" src="JS/menu.js"></script>	
	<script type="text/javascript" src="JS/process.js"></script>
	<!-- Calendar: Start -->
	<link type="text/css" rel="stylesheet" href="date/src/css/jscal2.css" />
	<link id="skin-steel" title="Steel" type="text/css" rel="alternate stylesheet" href="date/src/css/steel/steel.css" />
	<script src="date/src/js/jscal2.js"></script>
	<script src="date/src/js/lang/en.js"></script>
	<!-- Calendar: End -->	
	<!-- FancyBox: Start -->
	<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	
	<script type="text/javascript">
		$(document).ready(function() {
			$("a#example4").fancybox({
				'opacity'		: true,
				'overlayShow'	: false,
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'none'
			});

			$("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
		});
	</script>
	<title><?php echo genterate_page_title();?></title>

	<!-- FancyBox: End -->
	<!-- Pagination::Start-->
	<link href="css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="css/grey.css" rel="stylesheet" type="text/css" />
	<!-- Pagination::End-->
	
	<!-- Slider::Start-->
	<link href="css/slider/skitter.styles.css" type="text/css" media="all" rel="stylesheet" />
	<link href="css/slider/sexy-bookmarks-style.css" type="text/css" media="all" rel="stylesheet" />
	
	<script src="js/slider/jquery.easing.1.3.js"></script>
	<script src="js/slider/jquery.animate-colors-min.js"></script>
	<script src="js/slider/jquery.skitter.min.js"></script>
	<script src="js/slider/highlight.js"></script>
	<script src="js/slider/sexy-bookmarks-public.js"></script>
	<!-- Slider::End-->
	
</head>

<body id="wrapper_container">
	<div id="main_body_wrapper">
		<div id="wrapper">
