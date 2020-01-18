<?php
namespace Frugue\Store\Block;
use Magento\Framework\View\Element\Template as _P;
use Magento\Store\Model\Store as S;
use Magento\Store\Model\StoreManagerInterface as IStoreManager;
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
	final function post(S $s) {return df_post_h()->getPostData($this->getUrl('stores/store/switch'), [
		IStoreManager::PARAM_NAME => $s->getCode()
		/**
		 * 2020-01-18
		 * 1) "The store/country switcher at the frontend's header does not work":
		 * https://github.com/frugue/store/issues/1
		 * 2) @see \Magento\Store\Controller\Store\SwitchAction::execute():
		 *		$fromStoreCode = $this->_request->getParam('___from_store');
		 *		$requestedUrlToRedirect = $this->_redirect->getRedirectUrl();
		 *		$redirectUrl = $requestedUrlToRedirect;
		 *		$error = null;
		 *		try {
		 *			$fromStore = $this->storeRepository->get($fromStoreCode);
		 *			$targetStore = $this->storeRepository->getActiveStoreByCode($targetStoreCode);
		 *		}
		 * 		catch (StoreIsInactiveException $e) {
		 *			$error = __('Requested store is inactive');
		 *		}
		 * 		catch (NoSuchEntityException $e) {
		 *			$error = __("The store that was requested wasn't found. Verify the store and try again.");
		 *		}
		 * https://github.com/magento/magento2/blob/2.3.0/app/code/Magento/Store/Controller/Store/SwitchAction.php#L95-L113
		 */
		,'___from_store' => df_store_code()
	]);}

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