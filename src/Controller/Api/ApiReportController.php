<?php

namespace App\Controller\Api;

use App\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * The goal of this custom controller is to generate the link to report at ressource exposition.
 * Works for call of the collection and call of an item.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class ApiReportController extends AbstractController
{

	private $helper;

	public function __construct(UploaderHelper $helper)
	{
		$this->helper = $helper;
	}

	public function __invoke($data)
	{

		if($data instanceof Report){
			$data->setLink($this->helper->asset($data, 'file'));
		}

		else {
			foreach ($data as $report) {
				$report->setLink($this->helper->asset($report, 'file'));
			}
		}

		return $data;
	}
}