{**
 * templates/publishDataForm.tpl
 *
 * Copyright (c) 2019-2021 Lepidus Tecnologia
 * Copyright (c) 2020-2021 SciELO
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Extensions to Submission File Metadata Form
 *
 *}
{fbvFormSection list="true" title="Dataverse Plugin" translate=false}
	{fbvElement type="checkbox" label="plugins.generic.dataverse.submissionFileMetadata.publishData" id="publishData" checked=false}
{/fbvFormSection}

<script>
	$(function() {ldelim}
		$('input[id="publishData"]').next('a').on('click', (e)=> {
			window.open($dataverseTermsOfUseUrl, 'Window', 'width=600,height=550,screenX=100,screenY=100,toolbar=0,resizable=1,scrollbars=1');
			e.preventDefault();
		})
	{rdelim});
</script>