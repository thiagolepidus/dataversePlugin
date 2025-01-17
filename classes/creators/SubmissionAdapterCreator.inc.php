<?php

import('plugins.generic.dataverse.classes.adapters.SubmissionAdapter');
import('plugins.generic.dataverse.classes.adapters.AuthorAdapter');

class SubmissionAdapterCreator
{
    public function createSubmissionAdapter(Submission $submission): SubmissionAdapter
    {
        $locale = $submission->getLocale();
        $publication = $submission->getCurrentPublication();
        $apaCitation = new APACitation();

        $title = $publication->getLocalizedData('title', $locale);
        $authors = $this->retrieveAuthors($publication, $locale);
        $description = $publication->getLocalizedData('abstract', $locale);
        $keywords = $publication->getData('keywords')[$locale];
        $citation = $apaCitation->getFormattedCitationBySubmission($submission);
        $reference = array($citation, array());

        return new SubmissionAdapter($title, $authors, $description, $keywords, $reference);
    }

    private function retrieveAuthors(Publication $publication, string $locale): array
    {
        $authors =  $publication->getData('authors');
        $authorAdapters = [];

        foreach ($authors as $author) {
            $givenName = $author->getLocalizedGivenName($locale);
            $familyName = $author->getLocalizedFamilyName($locale);
            $affiliation = $author->getLocalizedData('affiliation', $locale);
            $email = $author->getData('email');

            $affiliation = !is_null($affiliation) ? $affiliation : "";
            $email = !is_null($email) ? $email : "";
            $authorAdapters[] = new AuthorAdapter($givenName, $familyName, $affiliation, $email);
        }

        return $authorAdapters;
    }
}
