casper.options.waitTimeout = 10000;
casper.test.begin('Make a checkout and check all necessary data on the success page', function suite(test) {

    var baseUrl = casper.cli.get('url');
    if (typeof baseUrl == 'undefined') {
        casper.test.fail('You need to specify base url ');
    }

    /* Check that tracking info is being collected on different events */
    casper.start(baseUrl + 'customer/account/login', function () {
        test.assertHttpStatus(200);
        this.test.pass('Login page is loaded');
        this.fill('form#login-form', {
            'login[username]' : storeConfig.customerLogin,
            'login[password]' : storeConfig.customerPassword
        }, true);
    });

    casper.waitForSelector('.customer-account-index').then(function() {
        test.assertHttpStatus(200);
        this.test.assertTitle('My Account', 'We are logged in!');
    });

    casper.thenOpen(baseUrl + storeConfig.firstTestProductUrl, function() {
        test.assertHttpStatus(200);
        this.click('.link-wishlist');
        this.click('.btn-cart');
    });

    casper.waitForSelector('.success-msg', function() {
       test.assertTitle('My Wishlist', 'The product has been added to wishlist');
       test.assertSelectorHasText('#segmentio-analytics-event',
        "analytics.track('Wishlisted Product'", 'Wishlist tracking code should be present on the page');
    });

    casper.thenClick('.btn-cart', function() {
        test.pass('Attempt to add product to the shopping cart');
    });

    casper.waitForUrl(baseUrl + 'checkout/cart', function() {
        this.test.assertTitle('Shopping Cart', 'The product has been added to the shopping cart');
        test.assertSelectorHasText('body', "analytics.track('Added Product'");
        this.click('.btn-checkout');
    });


    casper.waitForUrl(baseUrl + 'checkout/onepage', function() {
        test.assertHttpStatus(200);
        test.pass('We are on the checkout page');
        this.evaluate(function() {
            billing.newAddress(1);
        });
    });

    /* Billing address step */
    casper.waitUntilVisible('#opc-billing', function() {
        test.assertExists('input[name="billing[firstname]"]');
        test.assertExists('input[name="billing[lastname]"]');
        test.assertExists('input[name="billing[company]"]');
        test.assertExists('input[name="billing[street][]"]');
        test.assertExists('input[name="billing[city]"]');
        test.assertExists('select[name="billing[region_id]"]');
        test.assertExists('input[name="billing[postcode]"]');
        test.assertExists('select[name="billing[country_id]"]');
        test.assertExists('input[name="billing[telephone]"]');
        test.assertExists('input[name="billing[fax]"]');
        test.assertExists('input[name="billing[use_for_shipping]"]');
        this.fill('form#co-billing-form', {
            'billing[firstname]'    : storeConfig.login_user_firstname,
            'billing[lastname]'     : storeConfig.login_user_lastname,
            'billing[company]'      : storeConfig.user_address_company,
            'billing[street][]'     : storeConfig.user_address_street,
            'billing[city]'         : storeConfig.user_address_city,
            'billing[postcode]'     : storeConfig.user_address_postcode,
            'billing[telephone]'    : storeConfig.user_address_telephone,
            'billing[fax]'          : storeConfig.user_address_fax
        }, false);

        /* Set country and region_id dropdowns */
        this.evaluate(function(regionId, countryId) {
            function setSelectedValue(selectObj, valueToSet) {
                for (var i = 0; i < selectObj.options.length; i++) {
                    if (selectObj.options[i].text== valueToSet) {
                        selectObj.options[i].selected = true;
                        return;
                    }
                }
            }

            try {
                var regionIdObject = document.getElementById('billing:region_id');
                var countryIdObject = document.getElementById('billing:country_id');
                
                setSelectedValue(regionIdObject, regionId);
                setSelectedValue(countryIdObject, countryId);

                document.getElementById('billing:use_for_shipping_yes').checked = true;
                billing.save();
            } catch (err) {
                console.log(err);
            }
        }, { regionId: storeConfig.user_address_region, countryId: storeConfig.user_address_country });
        test.pass('Filling the billing address form and use this address as shipping');
    });

    /* Shipping method step */
    casper.waitUntilVisible('#checkout-step-shipping_method', function() {
        test.assertExists('.sp-methods');
        this.evaluate(function() {
            shippingMethod.save();
        });
        test.pass('Using flat rate as shipping method');
    });

    /* Payment method step */
    casper.waitUntilVisible('#checkout-step-payment', function() {
        this.evaluate(function() {
            document.getElementById('p_method_checkmo').checked = true;
            payment.save();
        });
        test.pass('Using "Check / Money order" as payment method');
    });

    /* Order review step */
    casper.waitUntilVisible('#checkout-step-review', function() {
        test.assertExists('#checkout-review-table');
        test.assertExists('button.btn-checkout');
        this.click('button.btn-checkout');
        test.pass('Placing the order');
        this.capture('error.png');
    });

    /* Order success page */
    casper.waitForUrl(baseUrl + 'checkout/onepage/success', function() {
        test.assertHttpStatus(200);
        test.assertExists('.checkout-onepage-success');
        test.assertSelectorHasText('#segmentio-analytics-order', "analytics.track('Completed Order'");
        test.assertSelectorHasText('#segmentio-analytics-order', '\"sku\":\"n2610\"');
        test.pass('The order has been placed successfully');
    });

    casper.run(function() {
        this.clear();
        phantom.clearCookies();
        test.done();
    });
});