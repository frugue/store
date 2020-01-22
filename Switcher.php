<?php
namespace Frugue\Store;
use Magento\Framework\App\ActionInterface as IAction;
use Magento\Framework\App\Response\RedirectInterface as IRedirect;
use Magento\Store\Model\StoreManagerInterface as IStoreManager;
// 2020-01-18
final class Switcher {
	/**
	 * 2020-01-18
	 * @used-by \Frugue\Shipping\Header::_toHtml()
	 * @used-by \Frugue\Store\Block\Switcher::post()
	 * @used-by \Frugue\Store\Plugin\Framework\App\FrontControllerInterface::aroundDispatch()
	 */
	const PATH = 'stores/store/switch';

	/**
	 * 2020-01-18
	 * @used-by \Frugue\Shipping\Header::_toHtml()
	 * @used-by \Frugue\Store\Block\Switcher::post()
	 * @used-by \Frugue\Store\Plugin\Framework\App\FrontControllerInterface::aroundDispatch()
	 * @param string $toStore
	 * @return array(string => string)
	 */
	static function params($toStore) {return df_url_param_redirect(df_replace_store_code_in_url($toStore)) + [
		IStoreManager::PARAM_NAME => $toStore
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
	];}
}