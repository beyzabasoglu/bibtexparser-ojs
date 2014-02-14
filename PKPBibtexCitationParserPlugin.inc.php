<?php

/**
 * @defgroup plugins_citationParser_bibtex
 */

/**
 * @file plugins/citationParser/bibtex/BibtexCitationParserPlugin.inc.php
 *
 *
 * @class BibtexCitationParserPlugin
 * @ingroup plugins_citationParser_bibtex
 *
 * @brief bibtex citation extraction connector plug-in.
 */


import('lib.pkp.plugins.citationParser.bibtexparser.PKPBibtexCitationParserPlugin');

class BibtexCitationParserPlugin extends PKPBibtexCitationParserPlugin {
  /**
   * Constructor
   */
  function BibtexCitationParserPlugin() {
    parent::PKPBibtexCitationParserPlugin();
  }
}

?>
