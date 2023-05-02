/**
 * PrestaShop Module - pitticafeed
 *
 * Copyright 2022 Pittica S.r.l.
 *
 * @category  Module
 * @package   Pittica\PrestaShop\Module\Feed
 * @author    Lucio Benini <info@pittica.com>
 * @copyright 2022 Pittica S.r.l.
 * @license   http://opensource.org/licenses/LGPL-3.0  The GNU Lesser General Public License, version 3.0 ( LGPL-3.0 )
 * @link      https://github.com/pittica/prestashop-feed
 */

$(document).ready(function () {
  var tpt = trovaprezzi.trustedprogram;
  if (window._tpt && tpt && tpt.key) {
    window._tpt.push({
      event: "setAccount",
      id: tpt.key,
    });
    window._tpt.push({
      event: "setOrderId",
      order_id: tpt.order.id,
    });
    window._tpt.push({
      event: "setEmail",
      email: tpt.order.email,
    });
    $(tpt.order.products).each(function (i, product) {
      window._tpt.push({
        event: "addItem",
        sku: product.sku,
        product_name: product.name,
      });
    });
    window._tpt.push({
      event: "setAmount",
      amount: tpt.order.total,
    });
    window._tpt.push({
      event: "orderSubmit",
    });
  }
});
