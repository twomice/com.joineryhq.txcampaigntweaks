/*global CRM, ts */
CRM.$(function ($) {
  if (!$('.crm-section.is_recur-section').length){
    $('#priceset').addClass('has-no-recur-section');
  }
});
