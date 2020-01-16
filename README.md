# JX One Click Order

v 1.1.8
  - fixed an issue with empty confirmation e-mails

v 1.1.7
UPD:
  - added an opportunity to add "One click order" button anywhere in the shop

v 1.1.6
FIX:
  - reworked admin part to remove all iframes according to the marketplace suggestion - "For security reasons, iframes are not allowed, please find a solution to bypass their use in your module."

v 1.1.5
FIX:
 - variables validation is improved

v 1.1.3
UPD:
 - ajax request handlers moved to ajax controller
 - copyright date updated to 2019
 - License.txt is added
FIX:
 - added cast to the $items variable in AdminJxOneClickOrder.php to ajaxProcessUpdateTemplateFieldsPosition method
 - replace short array definitions with full [] to array()
 - replace hardcoded text to language variables in success_message.tpl
 - fixed js error in admin panel when multistore enabled
 - fixed duplicating of notification counter when new order delete/create etc.

v 1.1.2
FIX: fixed hidden tabs on the settings page

v 1.1.1
FIX: fixed an issue with order result display in the admin part form("summary" tab)
UPD: added minimum shopping cart price rule limitation

v 1.1.0
- implemented compatibility with PSGDPR module