casper.test.begin('Check that a necessary code is present for logged in customer', function suite(test) {

    var baseUrl = casper.cli.get('url');
    if (typeof baseUrl == 'undefined') {
        casper.test.fail('You need to specify base url ');
    }
    
    casper.start(baseUrl + '/customer/account/login', function () {
        test.assertHttpStatus(200);
        this.test.pass('Login page is loaded');
        this.fill('form#login-form', {
            'login[username]' : storeConfig.customerLogin,
            'login[password]' : storeConfig.customerPassword
        }, true);
    });

    casper.then(function() {
        test.assertHttpStatus(200);
        this.test.assertTitle('My Account');
    });

    casper.then(function() {
        test.assertSelectorHasText('#segmentio-analytics-identify',
            '"email":"' + storeConfig.customerLogin + '"',
            'Customer tracking code is present on the page');
        test.assertSelectorHasText('#segmentio-analytics-identify', "analytics.track('Registered');");
    });

    casper.run(function() {
        this.clear();
        phantom.clearCookies();
        test.done();
    });
});