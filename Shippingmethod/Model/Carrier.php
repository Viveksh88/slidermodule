<?php
namespace Excellence\Shippingmethod\Model;
use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Simplexml\Element;
use Magento\Ups\Helper\Config;
use Magento\Framework\Xml\Security;
 
 
class Carrier extends AbstractCarrierOnline implements CarrierInterface
{

    const CODE = 'customshipping';
    protected $_code = self::CODE;
    protected $_request;
    protected $_result;
    protected $_baseCurrencyRate;
    protected $_xmlAccessRequest;
    protected $_localeFormat;
    protected $_logger;
    protected $configHelper;
    protected $_errors = [];
    protected $_isFixed = true;
    protected $_cartdata;
    protected $customerSession;
     
    public function __construct(
        
        \Magento\Checkout\Model\Cart $cartdata,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        Config $configHelper,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->_cartdata = $cartdata;
        $this->_localeFormat = $localeFormat;
        $this->configHelper = $configHelper;
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
    }
    protected function _doShipmentRequest(\Magento\Framework\DataObject $request)
    {
    }
   
    public function getCustomerGroup(){ //fetch Customer Group->General,VIP n VIP Special

        $customerData = $this->customerSession->getCustomer();
        $customerGroupId = $customerData->getGroupId();
        return $customerGroupId;

    }
    public function getProductWeight(){ //fetch product weight
        $items = $this->_cartdata->getQuote()->getAllItems();

        $weight = 0;
        foreach($items as $item) {
            $weight += ($item->getWeight() * $item->getQty()) ;        
        }
        return $weight;
    }
    public function getShippingCost(){ //to display shipping cost...
        $productWeight = $this->getProductWeight();
        $customerG = $this->getCustomerGroup();
        $fetchPrice = $this->_cartdata->getQuote();    
        $productTotalPrice =  $fetchPrice->getSubtotal(); 
        if($productWeight > 10){
            $shippingCost = ($productTotalPrice*0.15);//15% cost of total price of products.

        }elseif($customerG == 5){
            $shippingCost = ($productTotalPrice*0.08); //8% cost of total price of products.
            
        }else{
            $shippingCost = ($productTotalPrice*0.10);//10% cost of total price of products.
        }
        return $shippingCost;
        
    }
 
    public function getAllowedMethods()
    { 
    }
    public function collectRates(RateRequest $request) //main action to show shipping cost on frontend.
    {   
        $result = $this->_rateFactory->create();

        $method = $this->_rateMethodFactory->create();
        $productWeights = $this->getProductWeight(); //product weight.

        $shipppingData = $this->getShippingCost(); //product shippping % cost.
        $customerGroupDetails = $this->getCustomerGroup(); // customer Group.
        if($customerGroupDetails)
        {
            if($productWeights > 10){ // Product Weight > 10.
                $method->setCarrier($this->_code);
       
                $method->setCarrierTitle('15% Shipping Charge[Fixed Rate]');
                $method->setMethod($this->_code);
                $method->setMethodTitle('15% Shipping Charge[Flat Rate]');
                $method->setCost($shipppingData);
                $method->setPrice($shipppingData);
                $result->append($method);

            }elseif($customerGroupDetails == 4)
            {
                $method->setCarrier($this->_code);
       
                $method->setCarrierTitle('10% Shipping Charge[Fixed Rate]');
                $method->setMethod($this->_code);
                $method->setMethodTitle('10% Shipping Charge[Flat Rate]');
                $method->setCost($shipppingData);
                $method->setPrice($shipppingData);
                $result->append($method);
               
            }elseif($customerGroupDetails == 5)
            {
                $method->setCarrier($this->_code);
       
                $method->setCarrierTitle('8% Shipping Charge[Fixed Rate]');
                $method->setMethod($this->_code);
                $method->setMethodTitle('8% Shipping Charge[Flat Rate]');
                $method->setCost($shipppingData);
                $method->setPrice($shipppingData);
                $result->append($method);   
            }
            return $result;
        }
    }
     
    public function proccessAdditionalValidation(\Magento\Framework\DataObject $request) {
        return true;
    }
}