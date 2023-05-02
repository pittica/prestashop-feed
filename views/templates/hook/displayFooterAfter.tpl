{**
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
  *}

{if $trovaprezziActive}
<div class="block-trovaprezzi col-lg-6 col-md-12 col-sm-12">
  {if $trovaprezziUrl}
  <a href="{$trovaprezziUrl}" title="{l s='Seen on TrovaPrezzi' d='Modules.Pitticafeed.Front'}" target="_new">
  {/if}
    <img src="https://l1.trovaprezzi.it/buttons/recommendedby/it/tp_badge_partner_150_50.png" style="border:0px" alt="{l s='Seen on TrovaPrezzi' d='Modules.Pitticafeed.Front'}" />
  {if $trovaprezziUrl}
  </a>
  {/if}
</div>
{/if}