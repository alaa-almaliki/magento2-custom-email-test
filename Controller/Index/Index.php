<?php

namespace Alaa\CustomMailTest\Controller\Index;

use Alaa\CustomMail\Model\SendMailInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\App\ResponseInterface;
use Magento\Store\Model\StoreManager;

/**
 * Class Index
 * @package Alaa\CustomMailTest\Controller\Index
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Index extends Action
{
    /**
     * @var SendMailInterface
     */
    private $sendMail;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param SendMailInterface $sendMail
     * @param StoreManager $storeManager
     */
    public function __construct(Context $context, SendMailInterface $sendMail, StoreManager $storeManager)
    {
        parent::__construct($context);
        $this->sendMail = $sendMail;
        $this->storeManager = $storeManager;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $data = [
            'comment' => 'Hello this is a test message',
            'email' => 'john.doe@domain.com',
            'name' => 'John Doe',
        ];

        /**
         * wrap parameters values in array as they are called by call_user_func_array
         */
        $config = [
            'template_identifier' => ['contact_us'],
            'template_options' => [['area' => Area::AREA_FRONTEND ,'store' => $this->storeManager->getStore()->getId()]],
            'template_vars' => [$data],
            'from' => [['email' => 'john.doe@domain.com', 'name' => 'John Doe']],
            'to' => ['email' =>'tom.right@example.com', 'name' => 'Tom Right'],
            'cc' => ['email' =>'sarah.foxon@yahoo.com', 'name' => 'Sarah Foxon'],
            'bcc' => ['email' =>'Ahmed.Hassan@example.com', 'name' => 'Ahmed Hassan'],
        ];

        $this->sendMail->setConfig($config);
        try {
            $this->sendMail->send();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        echo 'Email has been sent';

    }
}