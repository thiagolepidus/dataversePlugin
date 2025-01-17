<?php

import('lib.pkp.classes.db.DAO');
import('plugins.generic.dataverse.classes.study.DataverseStudy');

use Illuminate\Database\Capsule\Manager as Capsule;

class DataverseStudyDAO extends DAO {

    function getStudy(int $studyId): DataverseStudy
    {
        $result = Capsule::table('dataverse_studies')
            ->where('study_id', $studyId)
            ->get();

        $study = null;
        foreach ($result->toArray() as $row) {
            $study = $this->returnStudyFromRow(get_object_vars($row));
        }

		return $study;
	}

    function getStudyBySubmissionId(int $submissionId): DataverseStudy
    {
        $result = Capsule::table('dataverse_studies')
            ->where('submission_id', $submissionId)
            ->get();

        $study = null;
        foreach ($result->toArray() as $row) {
            $study = $this->returnStudyFromRow(get_object_vars($row));
        }

		return $study;
    }

    function insertStudy(DataverseStudy $study): int
    {
		Capsule::table('dataverse_studies')
            ->insert(array(
                'submission_id'     =>  (int)$study->getSubmissionId(),
                'edit_uri'          =>  $study->getEditUri(),
                'edit_media_uri'    =>  $study->getEditMediaUri(),
                'statement_uri'     =>  $study->getStatementUri(),
                'persistent_uri'    =>  $study->getPersistentUri(),
                'data_citation'     =>  $study->getDataCitation()
            ));

        $study->setId($this->getInsertStudyId());
		return $study->getId();
	}

    function updateStudy(DataverseStudy $study): void
    {
        Capsule::table('dataverse_studies')
            ->where('study_id', $study->getId())
            ->update(array(
                'edit_uri'          =>  $study->getEditUri(),
                'edit_media_uri'    =>  $study->getEditMediaUri(),
                'statement_uri'     =>  $study->getStatementUri(),
                'persistent_uri'    =>  $study->getPersistentUri(),
                'data_citation'     =>  $study->getDataCitation()
            ));
	}	 

    function getInsertStudyId(): int
    {
		return $this->_getInsertId('dataverse_studies', 'study_id');
	}

    function returnStudyFromRow(array $row): DataverseStudy
    {
        $study = new DataverseStudy();
		$study->setId($row['study_id']);
		$study->setSubmissionId($row['submission_id']);
		$study->setEditUri($row['edit_uri']);
		$study->setEditMediaUri($row['edit_media_uri']);		
		$study->setStatementUri($row['statement_uri']);
		$study->setPersistentUri($row['persistent_uri']);
		$study->setDataCitation($row['data_citation']);
		
		return $study;
    }
}
?>