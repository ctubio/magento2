<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Backend\Setup;

use Magento\Framework\Config\Data\ConfigData;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\Setup\ConfigOptionsListInterface;
use Magento\Framework\Setup\Option\TextConfigOption;
use Magento\Framework\App\DeploymentConfig;

/*
 * Deployment configuration options needed for Backend module
 */
class ConfigOptionsList implements ConfigOptionsListInterface
{
    /**
     * Input key for the options
     */
    const INPUT_KEY_BACKEND_FRONTNAME = 'backend_frontname';

    /**
     * Path to the values in the deployment config
     */
    const CONFIG_PATH_BACKEND_FRONTNAME = 'backend/frontName';

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return [
            new TextConfigOption(
                self::INPUT_KEY_BACKEND_FRONTNAME,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                self::CONFIG_PATH_BACKEND_FRONTNAME,
                'Backend frontname',
                'admin'
            )
        ];
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createConfig(array $options, DeploymentConfig $deploymentConfig)
    {
        $configData = new ConfigData(ConfigFilePool::APP_CONFIG);

        if (isset($options[self::INPUT_KEY_BACKEND_FRONTNAME])) {
            $configData->set(self::CONFIG_PATH_BACKEND_FRONTNAME, $options[self::INPUT_KEY_BACKEND_FRONTNAME]);
        }

        return [$configData];
    }

    /**
     * {@inheritdoc}
     */
    public function validate(array $options)
    {
        $errors = [];
        if (isset($options[self::INPUT_KEY_BACKEND_FRONTNAME])
            && !preg_match('/^[a-zA-Z0-9_]+$/', $options[self::INPUT_KEY_BACKEND_FRONTNAME])
        ) {
            $errors[] = "Invalid backend frontname '{$options[self::INPUT_KEY_BACKEND_FRONTNAME]}'";
        }

        return $errors;
    }
}
