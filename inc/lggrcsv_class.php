<?php
/* speichern als UTF8 ohne BOM */

class LggrCsv {
	private $lggr=null;

	const aProperties = [
		'id',
		'date',
		'facility',
		'level',
		'host',
		'program',
		'pid',
		'message'
	];

	function __construct(Lggr &$oLggr) {
		$this->lggr =& $oLggr;
	} // constructor

	private function generiereDateiname() {
		$sCsvFilename = 'lggrarchive_' . date('Ymd') . '.csv';
		return $sCsvFilename;
	}

	function generiere() {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $this->generiereDateiname() . '"');

		$iCnt=0;
		$aEntries = $this->lggr->getArchived(0, 9999);

		foreach($aEntries as $entry) {
			if(0 == $iCnt) {
				// Spaltentitel
				foreach(self::aProperties as $sProp) {
					echo $sProp . ";";
				} // foreach
				echo "\n";
			} // if 0

			// Wertespalten
			foreach (self::aProperties as $sProp) {
				$sValue = $entry->$sProp;

				switch($sProp) {
					case 'id':
						echo "$sValue;";
						break;

					case 'message':
						echo '"' . strtr(utf8_decode($sValue), '"', "'") . "\";";
						break;

					default:
						echo '"' . utf8_decode($sValue) . "\";";
						break;
				} // switch
			} // foreach
			echo "\n";

			$iCnt++;
		} // foreach

	} // function

} // class