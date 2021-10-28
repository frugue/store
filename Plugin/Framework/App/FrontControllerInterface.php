<?php
namespace Frugue\Store\Plugin\Framework\App;
use Frugue\Core\Session as Sess;
use Frugue\Store\Switcher;
use Magento\Framework\App\Area as A;
use Magento\Framework\App\FrontControllerInterface as Sb;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\App\Request\Http;
use Magento\Framework\HTTP\PhpEnvironment\Response;
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
		# 2020-01-19
		# 1) "Disable the visitors redirection feature (based on the visitor's IP address)
		# for all Google's crawlers (otherwise some stores like Russian will never be indexed by Google)":
		# https://github.com/frugue/store/issues/3
		# 2) `https://frugue.com/insta_ru` should be redirected to `https://frugue.com/ru/all-bras.html?utm_source=instagram`:
		# https://github.com/frugue/site/issues/5
		if (!df_is_google_ua() && df_area_code_is(A::AREA_FRONTEND) && 'instagram' !== df_request('utm_source')) {
			$s = Sess::s(); /** @var Sess $s */
			if (!($c = $s->country())) /** @var string $c */ {
				$s->country($c = (df_is_localhost() ? 'HR' : df_visitor()->iso2()));
			}
			if (!$s->redirected()) {
				if (df_url_path_contains(Switcher::PATH)) {
					$s->redirected(true);
				}
				else {
					# «При первичном посещении клиента с IP адресом из Германии, Австрии, Швейцарии -
					# направляем во frugue Германия.
					#
					# При первичном посещении клиента с IP адресом из Франции, Бельгии, Люксембурга -
					# направляем во frugue Франция.
					#
					# При первичном посещении клиента с IP адресом из Великобритании, Ирландии,
					# а также Italy, Latvia, Bulgaria, Lithuania, Croatia, Cyprus, Malta, Czech Republic,
					# Netherlands, Poland, Estonia, Portugal, Finland, Romania, Slovakia, Slovenia, Greece,
					# Spain, Hungary, Sweden - направляем во frugue Великобритания.
					#
					# При первичном посещении клиента с IP адресом из всех других стран -
					# направляем его во frugue США.»
					$c = in_array($c, ['AT', 'CH', 'DE']) ? 'de' : (
						in_array($c, ['BE', 'FR', 'LU']) ? 'fr' : (
							df_eu($c) ? 'uk' : 'us'
						)
					);
					if ($c !== df_store_code()) {
						$res = df_o(Response::class);
						$res->setRedirect(df_url_frontend(Switcher::PATH, Switcher::params($c)));
					}
				}
			}
		}
		return $res ?: $f($r);
	}
}