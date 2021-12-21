<?php
import('lib.pkp.tests.DatabaseTestCase');
import('plugins.generic.dataverse.classes.DataverseDAO');
import('lib.pkp.classes.db.DAO');

class DataverseDAOTest extends DatabaseTestCase
{
    private int $contextId;
    private string $dataverseServer;
    private string $dataverse;
    private string $apiToken;

    private DataverseDao $dataverseDAO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contextId = 1;
        $this->dataverseServer = 'https://demo.dataverse.org';
        $this->dataverse = 'https://demo.dataverse.org/dataverse/dataverseDeExemplo';
        $this->apiToken = 'randomToken';
        $this->dataverseDAO =  new DataverseDAO();
    }

    protected function getAffectedTables(): array
    {
		return array('plugin_settings');
	}

    public function testCredentialsAddedInDB(): void
    {
        $pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
        $pluginSettingsDao->updateSetting($this->contextId, 'dataverseplugin', 'dataverseServer', $this->dataverseServer);
        $pluginSettingsDao->updateSetting($this->contextId, 'dataverseplugin', 'dataverse', $this->dataverse);
        $pluginSettingsDao->updateSetting($this->contextId, 'dataverseplugin', 'apiToken', $this->apiToken);
        $expectedCredentials = [$this->apiToken, $this->dataverse, $this->dataverseServer];
        $this->assertEquals($expectedCredentials, $this->dataverseDAO->getCredentialsFromDatabase($this->contextId));
    }

    public function testInsertCredentialsOnDatabase(): void
    {
        $this->dataverseDAO->insertCredentialsOnDatabase($this->contextId, $this->dataverseServer, $this->dataverse, $this->apiToken);
        $pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
        $result = $pluginSettingsDao->getPluginSettings($this->contextId, 'dataverseplugin');
        $credentials = [$result['apiToken'], $result['dataverse'] , $result['dataverseServer']];
        $expectedCredentials = [$this->apiToken, $this->dataverse, $this->dataverseServer];
        $this->assertEquals($expectedCredentials, $credentials);
    }
}
