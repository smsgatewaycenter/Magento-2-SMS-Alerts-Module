# Magento 2 SMS Gateway Center Module Integration

Magento 2 Sms Gateway Center SMS Alerts Notification to Magento Store Customers.

### About SMS Gateway Center's Magento 2 SMS Alerts Notification Module

One of the most used E-Commerce Script in the world is Magento. So we are giving away the Free Module to integrate with your shopping site 

Our Module helps the Magento store owners to send SMS alerts for the following events.

* New Order Event.
* Order Unhold Event.
* Order on Hold Event.
* Order Cancel Event.
* New Shipment Event.
* New Invoice Event.

The message templates are completely configurable from the Stores -&gt; Configuration page. You can enable and disable the each available template.


# Installation
 
Go to your Magento 2 root directory and run the following command:

```composer require smsgatewaycenter/magento2-sms-alert-notifications```

```bin/magento setup:upgrade```

```bin/magento cache:flush```

# Configuration

Module Configurations can be found in the Magento 2 admin panel under:

**Stores->Configuration->SMS Gateway Center-> SMS API Configuration.**

You can set SMP API Auth and other parameters such as API End Point, Sender ID, and Message Type.


**Stores->Configuration->SMS Gateway Center-> Admin Configuration.**

You can enable to edit message templates from this section.


# License

GNU GENERAL PUBLIC LICENSE Version 3
