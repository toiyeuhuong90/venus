<?php
/**
 * Venustheme
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Venustheme
 * @package    Ves_Themesettings
 * @copyright  Copyright (c) 2014 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Ves\Themesettings\Helper;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

class Theme extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Theme
     */
    private $currentTheme;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\DesignInterface $design,
        //\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PhpCookieManager $cookieManager,
        array $data = []
        ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->currentTheme = $design->getDesignTheme();
        $this->_coreRegistry = $registry;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->cookieManager = $cookieManager;
    }

    public function getCurrentTheme()
    {
        return $this->currentTheme;
    }

    public function getConfig($key, $package, $storeId = NULL, $default = NULL)
    {
        $store = $this->_storeManager->getStore($storeId);
        if($this->_coreRegistry->registry('ves_store')){
            $store = $this->_coreRegistry->registry('ves_store');
        }

        $vesCookie = $this->cookieManager->getCookie('vespaneltool');
        if($vesCookie){
            $settings = unserialize($vesCookie);
            if(isset($settings[$package.'/'.$key])){
                return $settings[$package.'/'.$key];
            }
        }

        $result = $this->scopeConfig->getValue(
            $package.'/'.$key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);

        if($result == '' && $default){
            return $default;
        }
        return $result;
    }

    public function getGeneralCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_general", $storeId, $default);
    }

    public function getHeaderCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_header", $storeId, $default);
    }

    public function getFooterCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_footer", $storeId, $default);
    }

    public function getProductListingCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_product_listing", $storeId, $default);
    }

    public function getCategoryPageCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_category_page", $storeId, $default);
    }

    public function getProductPageCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_product_page", $storeId, $default);
    }

    public function getContactPageCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_contact_page", $storeId, $default);
    }

    public function getCartPageCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_cart_page", $storeId, $default);
    }

    public function getCustomizationCfg($group, $storeId = NULL, $default = NULL )
    {
        return $this->getConfig($group, "ves_themesettings_customization", $storeId, $default);
    }
}