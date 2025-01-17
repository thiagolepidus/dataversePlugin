<?php

import('plugins.generic.dataverse.classes.DatasetModel');

class DatasetFactory
{
    public function build(SubmissionAdapter $submissionAdapter): DatasetModel
    {
        $title = $submissionAdapter->getTitle();
        $authors = $submissionAdapter->getAuthors();
        $description = $submissionAdapter->getDescription();
        $subject = $submissionAdapter->getKeywords();
        $isReferencedBy = $submissionAdapter->getReference();
        
        foreach ($authors as $author) {
            $contributors = array('contact' => $author->getAuthorEmail());
            $creator[] = $author->getFullName();
        }
        
        return new DatasetModel($title, $creator, $subject, $description, $contributors, '', '', array(), '', '', array(), '', '', $isReferencedBy);
    }
}
