<?php
namespace Frugue\Store\Plugin\Framework\App;
use Df\Customer\Model\Session as DfSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Area as A;
use Magento\Framework\App\FrontControllerInterface as Sb;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\HTTP\PhpEnvironment\Response;
use Magento\Store\Api\StoreResolverInterface as IStoreResolver;
// 2018-04-13
final class FrontControllerInterface {
	/**
	 * 2018-04-13
	 * «Redirect customers to a proper store by checking his IP address»:
	 * https://github.com/mage2pro/frugue.com/issues/2
	 * @see \Magento\Framework\App\FrontController::dispatch()
	 * https://github.com/magento/magento2/blob/2.1.9/lib/internal/Magento/Framework/App/FrontController.php#L34-L74
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param IRequest|Http $r
	 * @return Sb
	 */
	function aroundDispatch(Sb $sb, \Closure $f, IRequest $r) {
		$res = null; /** @var Response|null $res */
		if (!df_is_google_page_speed() && df_area_code_is(A::AREA_FRONTEND)) {
			$s = df_customer_session(); /** @var Session|DfSession $s */
			if (!($c = $s->getDfeFrugueCountry())) /** @var string $c */ {
				$s->setDfeFrugueCountry($c = (df_is_localhost() ? 'HR' : df_visitor()->iso2()));
			}
			if (!$s->getDfeFrugueRedirected()) {
				$urlSwitch = 'stores/store/switch';  /** @const string $urlSwitch */
				if (df_url_path_contains($urlSwitch)) {
					$s->setDfeFrugueRedirected(true);
				}
				else {
					/**
					 * «При первичном посещении клиента с IP адресом из Германии, Австрии, Швейцарии -
					 * направляем во frugue Германия.
					 *
					 * При первичном посещении клиента с IP адресом из Франции, Бельгии, Люксембурга -
					 * направляем во frugue Франция.
					 *
					 * При первичном посещении клиента с IP адресом из Великобритании, Ирландии,
					 * а также Italy, Latvia, Bulgaria, Lithuania, Croatia, Cyprus, Malta, Czech Republic,
					 * Netherlands, Poland, Estonia, Portugal, Finland, Romania, Slovakia, Slovenia, Greece,
					 * Spain, Hungary, Sweden - направляем во frugue Великобритания.
					 *
					 * При первичном посещении клиента с IP адресом из всех других стран -
					 * направляем его во frugue США.»
					 */
					$c = in_array($c, ['AT', 'CH', 'DE']) ? 'de' : (
						in_array($c, ['BE', 'FR', 'LU']) ? 'fr' : (
							df_eu($c) ? 'uk' : 'us'
						)
					);
					if ($c !== df_store_code()) {
						$res = df_o(Response::class);
						$res->setRedirect(df_url_frontend($urlSwitch, [IStoreResolver::PARAM_NAME => $c]));
					}
					$s->setDfeFrugueRedirectStarted(!!$res);
				}
			}
		}
		return $res ?: $f($r);
	}
}