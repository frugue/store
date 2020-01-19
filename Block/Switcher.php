<?php
namespace Frugue\Store\Block;
use Frugue\Store\Switcher as SwitcherM;
use Magento\Framework\View\Element\Template as _P;
use Magento\Store\Model\Store as S;
// 2018-07-25
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Switcher extends _P {
	/**
	 * 2018-07-25
	 * @param S $s
	 * @return string
	 */
	final function name(S $s) {return $this->escapeHtml(dftr($s->getCode(), $this->map()));}

	/**
	 * 2018-07-25
	 * @param S $s
	 * @return string
	 */
	final function post(S $s) {return df_post_h()->getPostData(
		$this->getUrl(SwitcherM::PATH), SwitcherM::params($s->getCode())
	);}

	/**
	 * 2018-07-25
	 * 2020-01-17
	 * "Add «Russia» to the countries dropdown in the frontend website header":
	 * https://github.com/frugue/site/issues/2
	 * @used-by name()
	 * @return array(string => array(string => string))
	 */
	private function map() {return dfc($this, function() {return dftr(df_lang(), [
		'de' => [
			'de' => 'Deutschland'
			,'uk' => 'Großbritannien'
			,'us' => 'USA'
			,'fr' => 'Frankreich'
			,'ru' => 'Russland'
		]
		,'en' => [
			'de' => 'Germany', 'uk' => 'United Kingdom', 'us' => 'United States', 'fr' => 'France', 'ru' => 'Russia'
		]
		,'fr' => ['de' => 'Allemagne', 'uk' => 'Royaume-Uni', 'us' => 'États-Unis', 'fr' => 'France', 'ru' => 'Russie']
		,'ru' => ['de' => 'Германия', 'uk' => 'Великобритания', 'us' => 'США', 'fr' => 'Франция', 'ru' => 'Россия']
	]);});}
}