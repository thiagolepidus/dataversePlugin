<?php

import('lib.pkp.tests.PKPTestCase');
import('lib.pkp.classes.submission.SubmissionFile');
import('plugins.generic.dataverse.classes.creators.SubmissionFileFactory');

class SubmissionFileFactoryTest extends PKPTestCase
{
    public function testSubmissionFileAdapterHasPublicFilesDirectoryInFilePath(): void
    {
        $submissionFile = new SubmissionFile();
        $submissionFile->setData('locale', 'en_US');
		$submissionFile->setData('path', '/assets/testSample.csv');
		$submissionFile->setData('name', 'sampleFileForTests.csv');
		$submissionFile->setData('publishData', true);
		$submissionFile->setData('genreId', 7);

        $factory = new SubmissionFileFactory();
        $submissionFileAdapter = $factory->build($submissionFile);
        $publicFilesDir = Config::getVar('files', 'files_dir');
        $expectedFilePath = $publicFilesDir. '/assets/testSample.csv';

        $this->assertEquals($expectedFilePath, $submissionFileAdapter->getPath());
    }
}
