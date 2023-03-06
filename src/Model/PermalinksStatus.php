<?php

namespace App\Model;
use JMS\Serializer\Annotation as JMS;

/**
* Class PermalinksStatus
 * @package App\Model
 */
class PermalinksStatus
{
	/**
	 * @var array|PermalinkStatus[]
	 * @JMS\Type("array<App\Model\PermalinkStatus>")
	 * @JMS\XmlList(inline =true, entry="permalink-status")
	 */
	private $permalinkStatus;

	/**
	 * @return PermalinkStatus[]|array
	 */
	public function getPermalinkStatus(): array
	{
		return $this->permalinkStatus;
	}
	/**
	 * @param PermalinkStatus[]|array $permalinkStatus
	 */
	public function setPermalinkStatus(array $permalinkStatus): void
	{
		$this->permalinkStatus = $permalinkStatus;
	}

	public function toString(): ?string
	{
		if(null==$this->permalinkStatus)
			return null;
		$result = "";
		foreach($this->permalinkStatus as $ps)
		{
			$result.="(".$ps->getPermalink().",".$ps->getStatus().")";
		}
		return $result;
	}
}
