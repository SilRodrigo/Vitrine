# Vitrine Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/rsilva/vitrine.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-document.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/rsilva/vitrine.svg?style=flat-square)](https://packagist.org/packages/rsilva/vitrine/stats)
[![Packagist](https://img.shields.io/packagist/dm/rsilva/vitrine.svg?style=flat-square)](https://packagist.org/packages/rsilva/vitrine/stats)

This module allows you to upload an image and link products to it, placing it anywhere in your website with pagebuilder.

![](https://github.com/SilRodrigo/Vitrine/blob/master/vitrine-gif.gif)

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Using](#using)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

### Composer installation

Run the following composer command:

```
composer require rsilva/vitrine
```

### Setup the module

Run the following magento command:

```
bin/magento module:enable Rsilva_Base Rsilva_ProductFilterApi Rsilva_Vitrine
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

This module allows you upload a image and link products to it.
Place a Vitrine anywhere on your website using pagebuilder feature.

## Using

### Creating a new Vitrine

 1. Access your Magento admin panel.
 2. On left menu access Rsilva -> Vitrine
 3. Click on ***New ambient***
 4. Input a name for your Vitrine
 5. Upload a new image or select one from gallery.

After that a bigger image from your upload will be shown below.
To start pinpointing your products, click on any place you'd like on that image.

A search input will appear, type a product name you want to link to that pinpoint and press enter.
All products that match your query will be listed, click the one you want. 

**That's it**, you tagged your first product, repeat the process to pin other products as well.
After you pinned all products you wanted, click on **Save** button.

### Placing your Vitrine anywhere on your website.

 1. Using a pagebuilder editor, place a text component anywhere you'd like.
 2. Click on that component as if you'd write something, on the option that will be show above, click on **Insert Widget**
 3. On **Widget Type**select Vitrine selector
 4. Choose a Vitrine that you created
 5. Click on **Insert Widget** and save your modifications.

And **That's it**, your Vitrine ambient is now ready on your frontend webpage.

## Support

Send a Hi to rodrigo.sil91@gmail.com and I will try to help.

## Author

- **Rodrigo Silva** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/SilRodrigo.svg?style=social)](https://github.com/SilRodrigo)


## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***


## **Reference documentation**

**Searching with repositories**

- https://developer.adobe.com/commerce/php/development/components/searching-with-repositories/


**Forms and Ui Components**

- https://developer.adobe.com/commerce/frontend-core/ui-components/components/form/

- https://developer.adobe.com/commerce/frontend-core/ui-components/components/html-content/

- https://devdocs.magento.com/guides/v2.3/ui_comp_guide/components/ui-listing-grid.html

- https://developer.adobe.com/commerce/frontend-core/ui-components/components/image-uploader/

- https://developer.adobe.com/commerce/frontend-core/javascript/jquery-widgets/alert/

  
**Knockout**

- https://developer.adobe.com/commerce/frontend-core/ui-components/concepts/knockout-bindings/

  
**Widget**

- https://webkul.com/blog/create-custom-widget-type-magento2/


**jQuery**

- https://developer.adobe.com/commerce/frontend-core/javascript/jquery-widgets/

- https://developer.adobe.com/commerce/frontend-core/javascript/jquery-widgets/loader/


**Javascript**

- https://developer.adobe.com/commerce/frontend-core/javascript/init/


**Frontend**

- https://meetanshi.com/blog/add-custom-less-file-in-magento-2/
