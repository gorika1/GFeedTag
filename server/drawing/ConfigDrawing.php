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
				'Hashtag' => $hashtag[ 'hashtag' ],
			);
		} // end foreach

		$this->setList( 'hashtags' );
		$this->draw( 'Hashtags' );
	} // end drawHashtags
} // end ConfigDrawing