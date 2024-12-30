{* Use the default layout *}
{include file="CRM/Report/Form.tpl"}
{* Append our descriptive text about Screening Rules *}
{if $botScreeningRules}
  <div class="help">
    <h3>* Screening Rules:</h3>
    <p>The following rules, named in the column "Screening Rule", describe the logic used to identify each contact as potential bot spam.
    <dl>
    {foreach from=$botScreeningRules item=botScreeningRuleDescription key=botScreeningRuleKey}
      <dt><strong>{$botScreeningRuleKey}</strong></dt><dd><pre>{$botScreeningRuleDescription}</pre></dd>
    {/foreach}
    </dl>
  </div>
{/if}
