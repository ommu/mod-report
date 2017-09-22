<?php

class FrontReportAdd extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->render('front_report_add',array(
			'url' => $url,
		));	
	}
}
