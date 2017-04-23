
jQuery(function($){
  
  var downArrowIcon = $("<i class='fa fa-angle-down'></i>");
  var homeIcon = $("<i class='fa fa-home'></i>");
  
  $(".ie7 .main-navigation ul.menu > li.menu-item-has-children > a").append(downArrowIcon);
  $(".ie7 .woocommerce-breadcrumb a:first-of-type").prepend(homeIcon);
  
  $(".tax-product_cat .products .product, .search-results .products .product").css({clear: 'none'});
  $(".tax-product_cat .products .product, .search-results .products .product").after("<br class='clearfix'>");
  
  $(".localshop-sorting").after("<div class='clearfix'>");
  $("#reviews .commentlist li").after("<br class='clearfix'>");
  
});
