{**
 * templates/dataverseAuthForm.tpl
 *
 * Copyright (c) 2019-2021 Lepidus Tecnologia
 * Copyright (c) 2020-2021 SciELO
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Dataverse plugin auth form
 *
 *}
<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#dataverseAuthForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="dataverseAuthForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" 
	plugin=$pluginName verb="settings" save=true}">
	<div id="description">{translate key="plugins.generic.dataverse.description"}</div>
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="translationAndGlossarySettingsFormNotification"}
	{fbvFormArea id="authForm"}
		<div id="authForm">
			{fbvFormSection list=true}
			<label class="label">{fieldLabel name="dataverseServer" required="true" key="plugins.generic.dataverse.settings.dataverseServer"}</label>
			{fbvElement type="url" id="dataverseServer" value=$dataverseServer|escape size=$fbvStyles.size.MEDIUM}
			<label class="sub_label">{translate key="plugins.generic.dataverse.settings.dataverseServerDescription"}</label>

			<label class="label">{fieldLabel name="dataverse" required="true" key="plugins.generic.dataverse.settings.dataverse"}</label>
			{fbvElement type="url" id="dataverse" value=$dataverse|escape size=$fbvStyles.size.MEDIUM}
			<label class="sub_label">{translate key="plugins.generic.dataverse.settings.dataverseDescription"}</label>
			
			<label class="label">{fieldLabel name="apiToken" required="true" key="plugins.generic.dataverse.settings.token"}</label>
			{fbvElement type="text" id="apiToken" value=$apiToken|escape size=$fbvStyles.size.MEDIUM}
			<label class="sub_label">{translate key="plugins.generic.dataverse.settings.tokenDescription"}</label>
			{/fbvFormSection}
			{fbvFormButtons}
		</div>
	{/fbvFormArea}
</form>