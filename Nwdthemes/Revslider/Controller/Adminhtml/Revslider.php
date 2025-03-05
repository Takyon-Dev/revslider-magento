<?php

namespace Nwdthemes\Revslider\Controller\Adminhtml;

use \Nwdthemes\Revslider\Model\Revslider\RevSliderFunctions;
use \Nwdthemes\Revslider\Model\Revslider\Admin\RevSliderAdmin;
use \Nwdthemes\Revslider\Model\Revslider\Admin\Includes\RevSliderTracking;
use \Nwdthemes\Revslider\Model\Revslider\Front\RevSliderFront;

abstract class Revslider extends \Magento\Backend\App\Action {

    /**
     * @var \Nwdthemes\Revslider\Helper\Framework
     */
    protected $_frameworkHelper;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Nwdthemes\Revslider\Helper\Framework $frameworkHelper,
        \Nwdthemes\Revslider\Helper\Plugin $pluginHelper
    ) {
        $this->_frameworkHelper = $frameworkHelper;

        parent::__construct($context);

        $this->_frameworkHelper->add_action('before_plugins_loaded', array('\Nwdthemes\Revslider\Model\Revslider\Front\RevSliderFront', 'create_tables'));
        $this->_frameworkHelper->add_action('before_plugins_loaded', array('\Nwdthemes\Revslider\Model\Revslider\Admin\Includes\RevSliderPluginUpdate', 'do_update_checks'));

        new RevSliderFront();

        $this->_frameworkHelper->do_action('before_plugins_loaded');
        $pluginHelper->deactivateOldPlugins();
        $pluginHelper->loadPlugins($this->_frameworkHelper);

        new RevSliderTracking();
        new RevSliderAdmin();

        $this->_frameworkHelper->do_action('init');
        $this->_frameworkHelper->do_action('admin_init');
    }

}
