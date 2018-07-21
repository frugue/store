<?php
namespace Frugue\Store\Plugin\Store\Block;
use Magento\Store\Block\Switcher as Sb;
use Magento\Store\Model\Group as G;
final class Switcher {
	/**
	 * 2018-03-11 https://www.upwork.com/d/contracts/19688867
	 * @see \Magento\Store\Block\Switcher::getGroups()
	 * https://github.com/magento/magento2/blob/2.1.9/app/code/Magento/Store/Block/Switcher.php#L117-L153
	 * @param Sb $sb
	 * @param G[] $r
	 * @return G[]
	 */
	function afterGetGroups(Sb $sb, array $r) {
		$map = dftr(df_lang(), [
			'de' => ['de' => 'Deutschland', 'uk' => 'Großbritannien', 'us' => 'USA', 'fr' => 'Frankreich']
			,'en' => ['de' => 'Germany', 'uk' => 'United Kingdom', 'us' => 'United States', 'fr' => 'France']
			,'fr' => ['de' => 'Allemagne', 'uk' => 'Royaume-Uni', 'us' => 'États-Unis', 'fr' => 'France']
		]);
		return array_map(function(G $g) use($map) {return
			$g->setName(dftr($g->getDefaultStore()->getCode(), $map))
		;}, $r);
	}
}