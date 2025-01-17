<?php
/**
 * @file plugins/generic/dataverse/DataversePlugin.inc.php
 *
 * Copyright (c) 2019-2021 Lepidus Tecnologia
 * Copyright (c) 2020-2021 SciELO
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class DataversePlugin
 * @ingroup plugins_generic_dataverse
 *
 * @brief dataverse plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');
import('classes.notification.NotificationManager');
import('plugins.generic.dataverse.classes.creators.DataversePackageCreator');
import('plugins.generic.dataverse.classes.creators.SubmissionAdapterCreator');
import('plugins.generic.dataverse.classes.creators.DataverseServiceFactory');
import('plugins.generic.dataverse.classes.creators.DatasetFactory');
import('plugins.generic.dataverse.classes.api.DataverseClient');
import('plugins.generic.dataverse.classes.api.DataverseService');
import('plugins.generic.dataverse.classes.DataverseConfiguration');
import('plugins.generic.dataverse.classes.study.DataverseStudyDAO');
import('plugins.generic.dataverse.classes.APACitation');
import('plugins.generic.dataverse.handlers.TermsOfUseHandler');

class DataversePlugin extends GenericPlugin {

	/**
	 * @see LazyLoadPlugin::register()
	 */
	public function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path, $mainContextId);
		$dataverseStudyDAO = new DataverseStudyDAO();
		$this->import('classes/controllers/DataverseFormController');
		$dataverseController = new DataverseFormController($this);
		DAORegistry::registerDAO('DataverseStudyDAO', $dataverseStudyDAO);
		HookRegistry::register('submissionsubmitstep4form::validate', array($this, 'dataverseDepositOnSubmission'));
		HookRegistry::register('Templates::Preprint::Main', array($this, 'addDataCitationSubmission'));
		HookRegistry::register('Publication::publish', array($this, 'publishDeposit'));
		HookRegistry::register('LoadComponentHandler', array($this, 'setupTermsOfUseHandler'));
		return $success;
	}

	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	public function getDisplayName() {
		return __('plugins.generic.dataverse.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	public function getDescription() {
		return __('plugins.generic.dataverse.description');
	}
	
	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $actionArgs) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled() ? array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			) : array(),
			parent::getActions($request, $actionArgs)
		);
	}

	/**
	 * @see Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				$contextId = ($context == null) ? 0 : $context->getId();

				$this->import('classes.form.DataverseAuthForm');
				$form = new DataverseAuthForm($this, $contextId);
				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						$notificationManager = new NotificationManager();
						$notificationManager->createTrivialNotification($request->getUser()->getId());
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
					$form->display();
				}
				
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}
	
	public function getDataverseConfiguration(int $contextId): DataverseConfiguration {
		return new DataverseConfiguration($this->getSetting($contextId, 'apiToken'), $this->getSetting($contextId, 'dataverseServer'), $this->getSetting($contextId, 'dataverse'));	
	}

	function dataverseDepositOnSubmission(string $hookName, array $params): void {
		$form =& $params[0];
		$context = $form->context;
		$contextId = $context->getId();
        $submission = $form->submission;

		$serviceFactory = new DataverseServiceFactory();
		$service = $serviceFactory->build($this->getDataverseConfiguration($contextId));
		$service->setSubmission($submission);
		if($service->hasDataSetComponent()){
			$service->depositPackage();
		}
	}

	function publishDeposit(string $hookName, array $params): void {
		$submission = $params[2];
		$contextId = $submission->getData("contextId");

		$serviceFactory = new DataverseServiceFactory();
		$service = $serviceFactory->build($this->getDataverseConfiguration($contextId));
		$service->setSubmission($submission);
		$service->releaseStudy();
	}

	function addDataCitationSubmission(string $hookName, array $params): bool {
		$templateMgr =& $params[1];
		$output =& $params[2];

		$submission = $templateMgr->getTemplateVars('preprint');
		$dataverseStudyDao = DAORegistry::getDAO('DataverseStudyDAO');			 
		$study = $dataverseStudyDao->getStudyBySubmissionId($submission->getId());

		if(isset($study)) {
			$apaCitation = new APACitation();
			$dataCitation = $apaCitation->getCitationAsMarkupByStudy($study);
			$templateMgr->assign('dataCitation', $dataCitation);
			$output .= $templateMgr->fetch($this->getTemplateResource('dataCitationSubmission.tpl'));
		}

		return false;
	}

	function getInstallMigration(): DataverseStudyMigration {
        $this->import('classes.migration.DataverseStudyMigration');
        return new DataverseStudyMigration();
    }

	function setupTermsOfUseHandler($hookName, $params) {
		$component = &$params[0];
		if ($component == 'plugins.generic.dataverse.handlers.TermsOfUseHandler') {
			return true;
		}
		return false;
	}
}

?>
