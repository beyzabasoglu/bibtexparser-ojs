<?php

/**
 * @defgroup plugins_citationParser_bibtex_filter
 */
/**
 * @file plugins/citationParser/bibtex/filter/BibTexCitationNlm30CitationSchemaFilter.inc.php
 *
 *
 * @class BibTexCitationNlm30CitationSchemaFilter
 * @ingroup plugins_citationParser_bibtex_filter
 *
 * @brief  Works on Bibtex
 *  journal citations.
 */
import('lib.pkp.plugins.metadata.nlm30.filter.Nlm30CitationSchemaFilter');
import("lib.pkp.plugins.citationParser.bibtex.filter.BibtexFilter");

class BibTexCitationNlm30CitationSchemaFilter extends Nlm30CitationSchemaFilter {
    /*
     * Constructor
     * @param $filterGroup FilterGroup
     */

    function BibTexCitationNlm30CitationSchemaFilter(&$filterGroup) {
        $this->setDisplayName('BibTex');

        parent::Nlm30CitationSchemaFilter($filterGroup);
    }

    //
    // Implement template methods from PersistableFilter
    //
	/**
     * @see PersistableFilter::getClassName()
     */
    function getClassName() {
        return 'lib.pkp.plugins.citationParser.bibtex.filter.BibTexCitationNlm30CitationSchemaFilter';
    }

    //
    // Implement template methods from Filter
    //
	/**
     * @see Filter::process()
     * @param $citationString string
     * @return MetadataDescription
     */
        function &process($citationString) {
        
        
        $parser = new Bibtexparser(array(
            'jan' => 'january',
            'feb' => 'february',
            'mar' => 'march',
            'apr' => 'april',
            'may' => 'may',
            'jun' => 'june',
            'jul' => 'july',
            'aug' => 'august',
            'sep' => 'september',
            'oct' => 'october',
            'nov' => 'november',
            'dec' => 'december'
        ));

        $metadata = array();

        try {
            $filteredString = $parser->parseString($citationString);
        } catch (Exception $exception) {
            error_log(print_r("catch!!!!!!!!!!!!!", 1));

            $metadata = array();
            // Make the meta-data fully NLM citation compliant
            $metadata = & $this->postProcessMetadataArray($metadata);

            // Create the NLM citation description
            return $this->getNlm30CitationDescriptionFromMetadataArray($metadata);
        }
        // initialize for metadata
        
        $this->convertToMetadata($filteredString, $metadata);
//        
        // Make the meta-data fully NLM citation compliant
        $metadata = & $this->postProcessMetadataArray($metadata);
                
        
        // Create the NLM citation description
        return $this->getNlm30CitationDescriptionFromMetadataArray($metadata);
    }

    public function convertToMetadata($entries, &$metadata) {

        // matrix for types
        // mandotary fields
        $article = array("author", "title", "journal", "year");
        $book = array("author or editor", "title", "publisher", "year");
        $booklet = array("title");
        $conference = array("author", "title", "booktitle", "year");
        $inbook = array("author or editor", "title", "pages", "year", "publisher");
        $incollection = array("author", "title", "booktitle", "year", "publisher");
        $inproceedings = array("author", "title", "booktitle", "year");
        $manual = array("title");
        $mastersthesis = array("author", "title", "school", "year");// school extracted in db.
        $phdthesis = array("author", "title", "school", "year");
        $proceedings = array("title", "year");
        $techreport = array("author", "title", "institution", "year");
        $unpublished = array("author", "title", "note");

        $isFormatted = false;

        foreach ($entries as $entry) {  // only one!
            // control the bibtex format
            switch ($entry["type"]) {
                case "book":
                    $isFormatted = $this->checkFormat($entry, $book);
                    break;
                case "article":
                    $isFormatted = $this->checkFormat($entry, $article);
                    break;
                case "booklet":
                    $isFormatted = $this->checkFormat($entry, $booklet);
                    break;
                case "conference":
                    $isFormatted = $this->checkFormat($entry, $conference);
                    break;
                case "inbook":
                    $isFormatted = $this->checkFormat($entry, $inbook);
                    break;
                case "incollection":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "inproceedings":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "manual":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "mastersthesis":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "phdthesis":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "proceedings":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "techreport":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
                case "unpublished":
                    $isFormatted = $this->checkFormat($entry, $incollection);
                    break;
            }
        }
            

        if ($isFormatted) {
            // save as metadata format(standart format in ojs filter)
            foreach ($entries as $entry) {  // only one!
                foreach ($entry["fields"] as $key => $val) {
                    switch ($key) {
                        case "pmid":
                            $metadata['pub-id[@pub-id-type="pmid"]'] = $val;
                            break;
                        case "doi":
                            $metadata['pub-id[@pub-id-type="doi"]'] = $val;
                            break;
                        case "author":
                            $metadata['author'] = $val;
                            break;
                        case "editor":
                            $metadata['editor'] = $val;
                            break;
                        case "journal":
                            $metadata['source'] = $val;
                            break;
                        case "address":
                            $metadata['publisher-loc'] = $val;
                            break;
                        case "publisher":
                            $metadata['publisher-name'] = $val;
                            break;
                        case "year":
                            $metadata['date'] = $val;
                            break;
               
                        case "title":
                            $metadata['article-title'] = $val;
                            break;
                        case "booktitle":
                            $metadata['article-title'] = $val;   // book title diye bir alan ekle.
                            break;
                        case "volume":
                            $metadata['volume'] = $val;
                            break;
                        case "issue":
                            $metadata['issue'] = $val;
                            break;
                        case "pages":
                            $page = explode("-", $val);
                            $metadata['fpage'] = $page[0];
                            $metadata['lpage'] = $page[1];
                            break;
                        case "note":
                            $metadata['comment'] = $val;
                            break;
                    }
                }
                $metadata['[@publication-type]'] = $entry["type"];  //  NLM30 format?
//            
            }
            
        } else
            return false;
    }

    public function checkFormat($entry, $type) {
        foreach ($type as $key => $val) {
            if ($val === "author or editor") {
                if (!isset($entry["fields"]["author"])) {  // if author is not set then search for editor
                    if (!isset($entry["fields"]["editor"])) {
                        // if editor is not set then we have parse error!
                        return false;
                    }
                }
            } else if (!isset($entry["fields"][$val])) {
                // if one them is not set then it is not in the correct form.
                return false;
            }
        }
        return true;
    }

}

?>
