<?php
namespace Frugue\Store\Plugin\UrlRewrite\Model\StoreSwitcher;
use Magento\Store\Api\Data\StoreInterface as IStore;
use Magento\UrlRewrite\Model\StoreSwitcher\RewriteUrl as Sb;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as R;
// 2020-01-18
final class RewriteUrl {
	/**
	 * 2020-01-18
	 * «The store/country switcher at the frontend's header does not work»: https://github.com/frugue/store/issues/1
	 * @see \Magento\UrlRewrite\Model\StoreSwitcher\RewriteUrl::switch()
	 * https://github.com/frugue/store/issues/1#issuecomment-575890443
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param IStore $fromStore
	 * @param IStore $targetStore
	 * @param IStore $redirectUrl
	 * @return string
	 */
	function aroundSwitch(Sb $sb, \Closure $f, IStore $fromStore, IStore $targetStore, string $redirectUrl): string {
		$targetUrl = $redirectUrl;
		$urlPath = ltrim(df_request_i($targetUrl)->getPathInfo(), '/');
		if ($targetStore->isUseStoreInUrl()) {
			// Remove store code in redirect url for correct rewrite search
			$storeCode = preg_quote($targetStore->getCode() . '/', '/');
			$pattern = "@^($storeCode)@";
			$urlPath = preg_replace($pattern, '', $urlPath);
		}
		$oldStoreId = $fromStore->getId();
		$oldRewrite = df_url_finder()->findOneByData([R::REQUEST_PATH => $urlPath, R::STORE_ID => $oldStoreId,]);
		if ($oldRewrite) {
			$targetUrl = $targetStore->getBaseUrl();
			// look for url rewrite match on the target store
			$currentRewrite = $this->findCurrentRewrite($oldRewrite, $targetStore);
			if ($currentRewrite) {
				$targetUrl .= $currentRewrite->getRequestPath();
			}
		}
		else {
			$targetUrl = $targetStore->getBaseUrl();
		}
		return $targetUrl;
	}

	/**
	 * 2020-01-18
	 * @see \Magento\UrlRewrite\Model\StoreSwitcher\RewriteUrl::findCurrentRewrite()
	 * https://github.com/magento/magento2/blob/2.3.3/app/code/Magento/UrlRewrite/Model/StoreSwitcher/RewriteUrl.php#L96-L120
	 * @param R $oldRewrite
	 * @param IStore $targetStore
	 * @return R|null
	 */
	private function findCurrentRewrite(R $oldRewrite, IStore $targetStore) {
		$currentRewrite = df_url_finder()->findOneByData([
			R::STORE_ID => $targetStore->getId(), R::TARGET_PATH => $oldRewrite->getTargetPath()
		]);
		if (!$currentRewrite) {
			$currentRewrite = df_url_finder()->findOneByData([
				R::REQUEST_PATH => $oldRewrite->getTargetPath(),
				R::STORE_ID => $targetStore->getId(),
			]);
		}
		return $currentRewrite;
	}
}