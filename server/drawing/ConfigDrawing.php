<?php

use Gear\Draw\Drawing;
require_once 'server/model/AdminModel.php';

class ConfigDrawing extends Drawing
{
	private $myAdmin;

	public function __construct()
	{
		parent::__construct();
		$this->myAdmin = new AdminModel();
	} // end __construct

	public function drawHashtags()
	{
		$hashtags = $this->myAdmin->getHashtags();

		foreach( $hashtags as $hashtag )
		{
			$this->list[] = array(
				'IdHashtag' => $hashtag[ 'idHashtag' ],
				'Hashtag' => $hashtag[ 'hashtag' ],
			);
		} // end foreach

		$this->setList( 'hashtags' );
		$this->draw( 'Hashtags' );
	} // end drawHashtags

	// Renderiza las palabras censuradas
	public function drawBlacklist()
	{
		$words = $this->myAdmin->getBlacklist();

		foreach( $words as $word )
		{
			$this->list[] = array(
				'IdPalabra' => $word[ 'idPalabra' ],
				'Palabra' => $word[ 'palabra' ],
			);
		} // end foreach

		// Renderiza la primera tabla
		$this->setList( 'blacklist' );
		$this->draw( 'Blacklist' );

	} // end drawHashtags
} // end ConfigDrawing