<?php

/**
 * @defgroup plugins_citationParser_bibtex
 */

/**
 * @file plugins/citationParser/regex/PKPBibtexCitationParserPlugin.inc.php
 *
 *
 * @class PKPBibtexCitationParserPlugin
 * @ingroup plugins_citationParser_bibtparser
 *
 * @brief parse bibtex format
 */


import('classes.plugins.Plugin');

class PKPBibtexCitationParserPlugin extends Plugin {
	/**
	 * Constructor
	 */
	function PKPBibtexCitationParserPlugin() {
		parent::Plugin();
	}


	//
	// Override protected template methods from PKPPlugin
	//
	/**
	 * @see PKPPlugin::register()
	 */
	function register($category, $path) {
		if (!parent::register($category, $path)) return false;
		$this->addLocaleData();
		return true;
	}

	/**
	 * @see PKPPlugin::getName()
	 */
	function getName() {
		return 'BibtexCitationParserPlugin';
	}

        
        /**
	 * @todo: diğer locale dosyalarını da ekle..
	 */
        
	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.citationParser.bibtex.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.citationParser.bibtex.description');
	}
}

?>
